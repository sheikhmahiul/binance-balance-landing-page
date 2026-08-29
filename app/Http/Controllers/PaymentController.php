<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show($order_number)
    {
        $order = Order::with('package')->where('order_number', $order_number)->firstOrFail();

        return view('payment', compact('order'));
    }

    public function confirm(Request $request, $order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();

        if ($request->has('transaction_ref')) {
            $order->transaction_ref = $request->input('transaction_ref');
        }

        $order->payment_status = 'submitted';
        $order->verification_status = 'under_review';
        $order->save();

        return redirect()->route('order.status', ['order_number' => $order->order_number])
            ->with('success', 'Payment status updated to Under Review.');
    }

    public function status($order_number)
    {
        $order = Order::with('package')->where('order_number', $order_number)->firstOrFail();

        return view('order.status', compact('order'));
    }
}
