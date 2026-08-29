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
                <span>SECURE CHECKOUT</span>
            </div>
        </div>
    </header>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Steps -->
        <div class="mb-10 flex items-center justify-center gap-4 text-xs font-mono">
            <div class="flex items-center gap-2 text-binance-yellow">
                <span class="w-6 h-6 rounded-full bg-binance-yellow text-black font-bold flex items-center justify-center">1</span>
                <span class="font-semibold">Customer Info</span>
            </div>
            <div class="w-12 h-0.5 bg-exchange"></div>
            <div class="flex items-center gap-2 text-gray-500">
                <span class="w-6 h-6 rounded-full bg-exchange text-gray-400 font-bold flex items-center justify-center">2</span>
                <span>TRC20 Payment</span>
            </div>
            <div class="w-12 h-0.5 bg-exchange"></div>
            <div class="flex items-center gap-2 text-gray-500">
                <span class="w-6 h-6 rounded-full bg-exchange text-gray-400 font-bold flex items-center justify-center">3</span>
                <span>Telegram Review</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Order Summary Card (Left/Top) -->
            <div class="lg:col-span-5">
                <div class="glass-card rounded-2xl p-6 border border-exchange space-y-6">
                    <h2 class="text-lg font-bold text-white font-mono border-b border-exchange pb-3">Selected Package</h2>
                    
                    <div>
                        <span class="text-xs uppercase tracking-wider text-binance-yellow font-mono font-bold bg-yellow-500/10 px-2.5 py-1 rounded border border-yellow-500/20">
                            {{ $package->name }}
                        </span>
                        <div class="mt-4 flex items-baseline gap-2">
                            <span class="text-4xl font-extrabold text-white font-mono">${{ number_format($package->price, 2) }}</span>
                            <span class="text-sm font-bold text-binance-yellow font-mono">{{ $package->currency }}</span>
                        </div>
                    </div>

                    <div class="space-y-3 text-xs border-t border-b border-exchange/60 py-4 text-gray-300 font-mono">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Virtual Balance:</span>
                            <span class="text-white font-semibold text-right capitalize">{{ $package->virtual_balance }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Payment Network:</span>
                            <span class="text-gray-200">USDT (TRC20)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Verification:</span>
                            <span class="text-yellow-400">Manual Telegram Review</span>
                        </div>
                    </div>

                    <!-- Security Assurance Notice -->
                    <div class="p-3 rounded-lg bg-[#0B0E11] border border-exchange/80 space-y-1.5 text-[11px] text-gray-400">
                        <div class="flex items-center gap-1.5 text-crypto-green font-semibold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            Strict Privacy Guarantee
                        </div>
                        <p>No exchange passwords, private keys, 2FA codes, or API credentials are ever requested.</p>
                    </div>
                </div>
            </div>

            <!-- Customer Details Form (Right) -->
            <div class="lg:col-span-7">
                <div class="glass-card rounded-2xl p-6 sm:p-8 border border-exchange">
                    <h2 class="text-xl font-bold text-white tracking-tight mb-2">Customer Order Details</h2>
                    <p class="text-xs text-gray-400 mb-6">Enter your contact details to generate your unique payment order reference.</p>

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 rounded-lg bg-red-950/80 border border-red-500/50 text-red-300 text-xs space-y-1">
                            <div class="font-bold flex items-center gap-1.5 text-red-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Please resolve the following errors:
                            </div>
                            <ul class="list-disc list-inside space-y-0.5 pt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('checkout.store', ['package' => $package->id]) }}" method="POST" class="space-y-5">
                        @csrf

                        <!-- Full Name -->
                        <div>
                            <label for="full_name" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">Full Name</label>
                            <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required placeholder="e.g. John Doe" class="w-full bg-[#0B0E11] border border-exchange rounded-lg px-4 py-3 text-white text-sm focus:border-binance-yellow focus:ring-1 focus:ring-binance-yellow focus:outline-none transition-colors">
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">Email Address (Gmail)</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="name@gmail.com" class="w-full bg-[#0B0E11] border border-exchange rounded-lg px-4 py-3 text-white text-sm focus:border-binance-yellow focus:ring-1 focus:ring-binance-yellow focus:outline-none transition-colors">
                        </div>

                        <!-- Compliance Confirmation Checkbox -->
                        <div class="pt-2">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" required class="mt-1 rounded bg-[#0B0E11] border-exchange text-binance-yellow focus:ring-0 focus:ring-offset-0">
                                <span class="text-xs text-gray-400 leading-relaxed">
                                    I understand this purchase is to get virtual live Binance balance for marketing and promoting.
                                </span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit" class="w-full bg-binance-yellow hover-binance-yellow text-black font-extrabold py-4 rounded-lg text-sm uppercase tracking-wider transition-all shadow-lg hover:shadow-yellow-500/25 flex items-center justify-center gap-2">
                                <span>Continue to Payment</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
