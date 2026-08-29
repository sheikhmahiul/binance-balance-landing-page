<?php

namespace App\Http\Controllers;

use App\Models\BalancePackage;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function show($id)
    {
        $package = BalancePackage::find($id);

        if (!$package) {
            $package = BalancePackage::where('is_active', true)->first();
        }

        if (!$package) {
            return redirect()->route('landing')->with('error', 'Package not found.');
        }

        return view('checkout', compact('package'));
    }

    public function store(Request $request, $id)
    {
        $package = BalancePackage::findOrFail($id);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telegram_username' => 'nullable|string|max:255',
        ], [
            'full_name.required' => 'Please enter your full name.',
            'email.required' => 'Please provide a valid Gmail address.',
        ]);

        $telegramUsername = !empty($validated['telegram_username']) ? ltrim(trim($validated['telegram_username']), '@') : 'N/A';
        $trc20Address = config('services.payment.trc20_address', 'TQG2Ry4k9N9tF1dYR1T9Hs1H4stDZ8mtyi');

        // Generate unique order number
        do {
            $orderNumber = 'ORD-' . strtoupper(Str::random(8));
        } while (Order::where('order_number', $orderNumber)->exists());

        $order = Order::create([
            'order_number' => $orderNumber,
            'balance_package_id' => $package->id,
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'telegram_username' => $telegramUsername,
            'amount' => $package->price,
            'currency' => $package->currency,
            'payment_network' => 'TRC20',
            'payment_address' => $trc20Address,
            'payment_status' => 'pending',
            'verification_status' => 'waiting',
        ]);

        return redirect()->route('payment.show', ['order' => $order->order_number]);
    }
}
