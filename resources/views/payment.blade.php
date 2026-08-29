@extends('layouts.app')

@section('content')
<div class="min-h-screen py-12 lg:py-20 relative" x-data="{ copied: false }">
    <!-- Header -->
    <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
        <div class="flex items-center justify-between border-b border-exchange pb-6">
            <a href="{{ route('landing') }}" class="flex items-center gap-2.5">
                <img src="{{ asset('images/logo.png') }}" alt="Binance balance" class="w-8 h-8 rounded-full object-contain">
                <span class="font-extrabold text-xl tracking-tight font-mono"><span class="text-binance-yellow">Binance</span> <span class="text-white">balance</span></span>
            </a>
            <div class="flex items-center gap-2 text-xs font-mono text-yellow-400 bg-yellow-500/10 px-3 py-1 rounded border border-yellow-500/20">
                <span class="w-2 h-2 rounded-full bg-yellow-400 animate-ping"></span>
                <span>AWAITING PAYMENT</span>
            </div>
        </div>
    </header>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Steps -->
        <div class="mb-10 flex items-center justify-center gap-4 text-xs font-mono">
            <div class="flex items-center gap-2 text-crypto-green">
                <span class="w-6 h-6 rounded-full bg-crypto-green text-black font-bold flex items-center justify-center">✓</span>
                <span>Customer Info</span>
            </div>
            <div class="w-12 h-0.5 bg-crypto-green"></div>
            <div class="flex items-center gap-2 text-binance-yellow">
                <span class="w-6 h-6 rounded-full bg-binance-yellow text-black font-bold flex items-center justify-center">2</span>
                <span class="font-semibold">TRC20 Payment</span>
            </div>
            <div class="w-12 h-0.5 bg-exchange"></div>
            <div class="flex items-center gap-2 text-gray-500">
                <span class="w-6 h-6 rounded-full bg-exchange text-gray-400 font-bold flex items-center justify-center">3</span>
                <span>Telegram Review</span>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6 sm:p-8 border border-exchange space-y-8">
            <!-- Order Header Banner -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-exchange gap-4">
                <div>
                    <span class="text-xs text-gray-400 font-mono">ORDER REFERENCE</span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white font-mono tracking-tight">{{ $order->order_number }}</h1>
                </div>
                <div class="text-left sm:text-right">
                    <span class="text-xs text-gray-400 font-mono">AMOUNT DUE</span>
                    <div class="text-2xl sm:text-3xl font-extrabold text-binance-yellow font-mono">${{ number_format($order->amount, 2) }} <span class="text-base text-gray-300">USDT</span></div>
                </div>
            </div>

            <!-- Important Warning Banner -->
            <div class="p-4 rounded-xl bg-red-950/40 border border-red-500/50 flex items-start gap-3">
                <div class="w-7 h-7 rounded-full bg-red-900/80 text-crypto-red flex items-center justify-center font-bold text-sm shrink-0">!</div>
                <div class="text-xs text-red-200 space-y-1">
                    <div class="font-bold text-red-100">IMPORTANT NETWORK WARNING</div>
                    <p class="leading-relaxed">
                        Send USDT only through the <strong class="text-white underline">TRC20 network (Tron Chain)</strong>. Payments sent via ERC20, BEP20, or other networks cannot be processed.
                    </p>
                </div>
            </div>

            <!-- TRC20 Wallet Address Section -->
            <div class="space-y-4">
                <label class="block text-base sm:text-lg font-bold text-white uppercase tracking-wider font-mono flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-binance-yellow animate-pulse"></span>
                    USDT — TRC20 Payment Address
                </label>

                <!-- Copy Address Box -->
                <div class="flex flex-col sm:flex-row items-stretch gap-3 bg-[#0B0E11] p-4 rounded-xl border border-binance-yellow/40 shadow-lg">
                    <div class="flex-grow font-mono text-base sm:text-lg lg:text-xl text-binance-yellow font-extrabold break-all self-center px-2 tracking-wide">
                        {{ $order->payment_address }}
                    </div>
                    <button @click="navigator.clipboard.writeText('{{ $order->payment_address }}'); copied = true; setTimeout(() => copied = false, 3000)" 
                            class="bg-exchange-hover hover:bg-binance-yellow hover:text-black text-gray-200 font-bold px-5 py-3 rounded-lg text-xs font-mono transition-all flex items-center justify-center gap-2 shrink-0">
                        <template x-if="!copied">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 012-2v-8a2 2 0 01-2-2h-8a2 2 0 01-2 2v8a2 2 0 012 2z"></path></svg>
                                Copy Address
                            </span>
                        </template>
                        <template x-if="copied">
                            <span class="text-crypto-green flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Address Copied!
                            </span>
                        </template>
                    </button>
                </div>
            </div>

            <!-- Simulated Package Notice -->
            <div class="p-4 rounded-xl bg-[#0B0E11] border border-exchange text-xs text-gray-400 space-y-1 font-mono">
                <div class="text-gray-200 font-semibold">Package Information:</div>
                <p>Purchased Package: <span class="text-white">{{ $order->package->name }}</span> (${{ number_format($order->amount, 2) }} USDT)</p>
                <p>Virtual Balance: <span class="text-yellow-400 capitalize">{{ $order->package->virtual_balance }}</span></p>
                <p class="text-[11px] text-gray-500 pt-1">Get virtual live Binance balance for marketing and promoting.</p>
            </div>

            <!-- Action Buttons: Telegram & Confirmation -->
            <div class="pt-4 border-t border-exchange space-y-4">
                <!-- Telegram Verification CTA & Instructions -->
                <div class="p-4 rounded-xl bg-blue-950/30 border border-blue-500/30 space-y-3">
                    <div class="flex items-center gap-2 text-sm font-bold text-cyan-400">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.01-.03.01-.14-.07-.2-.08-.06-.19-.04-.27-.02-.12.02-1.96 1.25-5.54 3.69-.52.36-1 .54-1.43.53-.47-.01-1.38-.27-2.05-.48-.83-.27-1.49-.42-1.43-.88.03-.24.37-.49 1.02-.75 3.98-1.73 6.64-2.87 7.97-3.43 3.79-1.58 4.58-1.86 5.09-1.87.11 0 .37.03.54.18.14.12.18.28.2.45-.01.07.01.23 0 .38z"/></svg>
                        <span>Send Payment Proof to Telegram</span>
                    </div>
                    
                    <div class="text-xs text-gray-300 space-y-1.5 leading-relaxed font-mono">
                        <p class="text-yellow-400 font-semibold">After completing your TRC20 transfer, please send to our Telegram support (<a href="https://t.me/Binance_Balance_4U" target="_blank" class="underline text-cyan-300 font-bold">@Binance_Balance_4U</a>):</p>
                        <ul class="list-disc list-inside space-y-1 pl-1 text-gray-200">
                            <li><strong>Payment Screenshot</strong> (Transaction confirmation image)</li>
                            <li><strong>Sender USDT ID / Wallet Address</strong> (The account/address you sent payment from)</li>
                            <li><strong>Order Reference Number:</strong> <span class="text-binance-yellow font-bold">{{ $order->order_number }}</span></li>
                        </ul>
                    </div>

                    <a href="{{ $order->telegram_url }}" target="_blank" rel="noopener noreferrer" 
                       class="w-full bg-[#229ED9] hover:bg-[#1d8cb0] text-white font-extrabold py-3.5 rounded-lg text-xs uppercase tracking-wider transition-all shadow-lg hover:shadow-cyan-500/25 flex items-center justify-center gap-2 mt-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.01-.03.01-.14-.07-.2-.08-.06-.19-.04-.27-.02-.12.02-1.96 1.25-5.54 3.69-.52.36-1 .54-1.43.53-.47-.01-1.38-.27-2.05-.48-.83-.27-1.49-.42-1.43-.88.03-.24.37-.49 1.02-.75 3.98-1.73 6.64-2.87 7.97-3.43 3.79-1.58 4.58-1.86 5.09-1.87.11 0 .37.03.54.18.14.12.18.28.2.45-.01.07.01.23 0 .38z"/></svg>
                        <span>Open Telegram Support (@Binance_Balance_4U)</span>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
