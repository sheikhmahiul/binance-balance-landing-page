@extends('layouts.app')

@section('content')
<div class="min-h-screen py-12 lg:py-20 relative">
    <!-- Header -->
    <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
        <div class="flex items-center justify-between border-b border-exchange pb-6">
            <a href="{{ route('landing') }}" class="flex items-center gap-2.5">
                <img src="{{ asset('images/logo.png') }}" alt="Binance balance" class="w-8 h-8 rounded-full object-contain">
                <span class="font-extrabold text-xl tracking-tight font-mono"><span class="text-binance-yellow">Binance</span> <span class="text-white">balance</span></span>
            </a>
            <div class="flex items-center gap-2 text-xs font-mono text-gray-400">
                <span class="w-2 h-2 rounded-full bg-crypto-green"></span>
                <span>ORDER STATUS</span>
            </div>
        </div>
    </header>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="glass-card rounded-2xl p-6 sm:p-10 border border-exchange text-center space-y-6">
            
            <!-- Status Badge Icon -->
            <div class="mx-auto w-16 h-16 rounded-full flex items-center justify-center 
                @if($order->verification_status === 'approved') bg-crypto-green/20 text-crypto-green border border-crypto-green/30
                @elseif($order->verification_status === 'rejected') bg-crypto-red/20 text-crypto-red border border-crypto-red/30
                @else bg-yellow-500/20 text-binance-yellow border border-yellow-500/30
                @endif">
                @if($order->verification_status === 'approved')
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                @elseif($order->verification_status === 'rejected')
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                @else
                    <svg class="w-8 h-8 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                @endif
            </div>

            <!-- Title & Order Reference -->
            <div>
                <span class="text-xs uppercase font-mono text-gray-400">STATUS UPDATE</span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight mt-1">
                    @if($order->verification_status === 'approved')
                        Access Pass Approved!
                    @elseif($order->verification_status === 'rejected')
                        Payment Verification Rejected
                    @else
                        Payment Verification Submitted
                    @endif
                </h1>
                <div class="mt-2 text-sm font-mono text-binance-yellow font-bold">
                    Order #{{ $order->order_number }}
                </div>
            </div>

            <!-- Status Pill -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full font-mono text-xs font-bold uppercase tracking-wider
                @if($order->verification_status === 'approved') bg-crypto-green/15 text-crypto-green border border-crypto-green/30
                @elseif($order->verification_status === 'rejected') bg-crypto-red/15 text-crypto-red border border-crypto-red/30
                @else bg-yellow-500/15 text-binance-yellow border border-yellow-500/30
                @endif">
                Status: {{ $order->formatted_status }}
            </div>

            <!-- Main Message Box -->
            <div class="p-4 rounded-xl bg-[#0B0E11] border border-exchange text-xs text-gray-300 space-y-2 text-left font-mono">
                @if($order->verification_status === 'approved')
                    <p class="text-crypto-green font-bold">🎉 Your payment of ${{ number_format($order->amount, 2) }} USDT has been verified!</p>
                    <p>Your unlimited virtual trading simulation balance is now active. You may log into the trading sandbox dashboard.</p>
                @elseif($order->verification_status === 'rejected')
                    <p class="text-crypto-red font-bold">⚠️ Payment Verification Unsuccessful</p>
                    <p>Our team could not locate a matching TRC20 transfer for your order. Please contact our Telegram support team with your transaction hash.</p>
                @else
                    <p class="text-gray-200 font-semibold">Your payment status is currently Under Review.</p>
                    <p>Please contact our Telegram verification channel with your order number if additional payment information is required.</p>
                @endif
            </div>

            <!-- Order Details Table -->
            <div class="border-t border-exchange pt-4 text-xs font-mono text-gray-400 space-y-2 text-left">
                <div class="flex justify-between"><span>Customer:</span><span class="text-white">{{ $order->full_name }}</span></div>
                <div class="flex justify-between"><span>Email:</span><span class="text-white">{{ $order->email }}</span></div>
                <div class="flex justify-between"><span>Telegram:</span><span class="text-binance-yellow">@{{ $order->telegram_username }}</span></div>
                <div class="flex justify-between"><span>Package:</span><span class="text-white">{{ $order->package->name }}</span></div>
                <div class="flex justify-between"><span>Amount:</span><span class="text-white">${{ number_format($order->amount, 2) }} USDT (TRC20)</span></div>
                @if($order->transaction_ref)
                    <div class="flex justify-between"><span>Transaction TXID:</span><span class="text-gray-300 truncate max-w-[200px]">{{ $order->transaction_ref }}</span></div>
                @endif
            </div>

            <!-- Action Button -->
            <div class="pt-4 border-t border-exchange space-y-3">
                <a href="{{ $order->telegram_url }}" target="_blank" rel="noopener noreferrer" 
                   class="w-full bg-[#229ED9] hover:bg-[#1d8cb0] text-white font-extrabold py-3.5 rounded-lg text-xs uppercase tracking-wider transition-all shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.01-.03.01-.14-.07-.2-.08-.06-.19-.04-.27-.02-.12.02-1.96 1.25-5.54 3.69-.52.36-1 .54-1.43.53-.47-.01-1.38-.27-2.05-.48-.83-.27-1.49-.42-1.43-.88.03-.24.37-.49 1.02-.75 3.98-1.73 6.64-2.87 7.97-3.43 3.79-1.58 4.58-1.86 5.09-1.87.11 0 .37.03.54.18.14.12.18.28.2.45-.01.07.01.23 0 .38z"/></svg>
                    <span>Contact Telegram Verification Support</span>
                </a>

                <a href="{{ route('landing') }}" class="block text-xs font-mono text-gray-500 hover:text-white transition-colors pt-2">
                    &larr; Return to Home Landing Page
                </a>
            </div>

            <!-- Non-withdrawable simulation disclaimer -->
            <p class="text-[11px] text-gray-500 pt-2 font-mono">
                Virtual funds are for simulation purposes and are not withdrawable or transferable as real cryptocurrency.
            </p>
        </div>
    </div>
</div>
@endsection
