<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\BalancePackage;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Simple admin authorization check via session
        if (!session('admin_authenticated')) {
            return view('admin.login');
        }

        $query = Order::with('package')->latest();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telegram_username', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('verification_status', $request->input('status'));
        }

        $orders = $query->paginate(15);
        $totalOrders = Order::count();
        $pendingCount = Order::whereIn('verification_status', ['waiting', 'under_review'])->count();
        $approvedCount = Order::where('verification_status', 'approved')->count();
        $totalRevenue = Order::where('verification_status', 'approved')->sum('amount');

        $trc20Address = config('services.payment.trc20_address', 'TQG2Ry4k9N9tF1dYR1T9Hs1H4stDZ8mtyi');
        $telegramHandle = config('services.telegram.username', 'Binance_Balance_4U');

        return view('admin.index', compact(
            'orders',
            'totalOrders',
            'pendingCount',
            'approvedCount',
            'totalRevenue',
            'trc20Address',
            'telegramHandle'
        ));
    }

    public function login(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $adminPassword = config('services.admin.password', 'admin123');

        if ($request->input('password') === $adminPassword) {
            session(['admin_authenticated' => true]);
            return redirect()->route('admin.index')->with('success', 'Logged into Admin Portal');
        }

        return back()->withErrors(['password' => 'Invalid admin access password.']);
    }

    public function logout()
    {
        session()->forget('admin_authenticated');
        return redirect()->route('admin.index');
    }

    public function updateStatus(Request $request, $id)
    {
        if (!session('admin_authenticated')) {
            return redirect()->route('admin.index')->with('error', 'Unauthorized access.');
        }

        $order = Order::findOrFail($id);

        $request->validate([
            'verification_status' => 'required|in:waiting,under_review,approved,rejected',
            'payment_status' => 'required|in:pending,submitted,verified,rejected',
            'transaction_ref' => 'nullable|string|max:255',
        ]);

        $order->verification_status = $request->input('verification_status');
        $order->payment_status = $request->input('payment_status');
        
        if ($request->has('transaction_ref')) {
            $order->transaction_ref = $request->input('transaction_ref');
        }

        $order->save();

        return redirect()->route('admin.index')->with('success', "Order #{$order->order_number} status updated to {$order->verification_status}.");
    }
}
