@extends('layouts.app')

@section('content')
<div x-data="{ 
    activeTab: 'overview',
    showTradeModal: false,
    tradeAsset: 'BTC',
    tradePrice: 96450.20,
    amount: 0.1,
    livePrices: {
        BTC: { price: '$96,450.20', change: '+3.14%', isPos: true },
        ETH: { price: '$3,420.80', change: '+1.85%', isPos: true },
        BNB: { price: '$640.50', change: '+4.20%', isPos: true },
        USDT: { price: '$1.00', change: '0.00%', isPos: true }
    }
}">

    <!-- Compact Exchange Header -->
    <header class="sticky top-0 z-40 bg-[#0B0E11]/90 backdrop-blur-md border-b border-exchange">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Left: Logo -->
            <div class="flex items-center gap-3">
                <a href="{{ route('landing') }}" class="flex items-center gap-2.5 group">
                    <img src="{{ asset('images/logo.png') }}" alt="Binance balance" class="w-8 h-8 rounded-full object-contain shadow-md group-hover:scale-105 transition-transform">
                    <span class="font-extrabold text-xl tracking-tight font-mono">
                        <span class="text-binance-yellow">Binance</span> <span class="text-white">balance</span>
                    </span>
                </a>
            </div>

            <!-- Center Nav -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-300">
                <a href="#markets" class="hover:text-binance-yellow transition-colors">Markets</a>
                <a href="#pricing" class="hover:text-binance-yellow transition-colors">Pricing</a>
            </nav>

            <!-- Right: Action CTA -->
            <div class="flex items-center gap-4">
                <a href="#pricing" class="hidden sm:inline-block text-xs font-semibold text-gray-400 hover:text-white transition-colors">
                    Login / Access
                </a>
                <a href="{{ route('checkout.show', ['package' => $package->id]) }}" class="bg-binance-yellow hover-binance-yellow text-black font-semibold px-5 py-2 rounded text-sm transition-all shadow-md hover:shadow-yellow-500/20 active:scale-95">
                    Buy Now
                </a>
            </div>
        </div>
    </header>

    <!-- Live Market Ticker Bar -->
    <div id="markets" class="bg-[#181A20] border-b border-exchange text-xs font-mono py-2.5 overflow-x-auto scrollbar-none">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between gap-6 min-w-[700px]">
            <div class="flex items-center gap-2 text-gray-400 font-semibold uppercase text-[11px]">
                <span class="w-2 h-2 rounded-full bg-crypto-green animate-pulse"></span>
                MARKETS ONLINE
            </div>
            <template x-for="(data, symbol) in livePrices" :key="symbol">
                <div class="flex items-center gap-2 bg-[#0B0E11] px-3 py-1 rounded border border-exchange/50">
                    <span class="font-bold text-gray-200" x-text="symbol + '/USDT'"></span>
                    <span class="text-gray-300" x-text="data.price"></span>
                    <span :class="data.isPos ? 'text-crypto-green' : 'text-crypto-red'" class="font-semibold" x-text="data.change"></span>
                </div>
            </template>
        </div>
    </div>

    <!-- HERO SECTION -->
    <section class="relative py-16 lg:py-24 overflow-hidden">
        <!-- Subtle Glow Background -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[350px] bg-yellow-500/10 rounded-full blur-[140px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto space-y-6">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-binance-yellow/10 border border-binance-yellow/30 text-binance-yellow text-xs font-bold tracking-widest uppercase">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    SIMULATION ACCESS
                </div>

                <!-- Headline -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight">
                    Activate Your <span class="text-binance-yellow">Binance</span> <span class="text-white">Virtual Live Balance</span>
                </h1>


                <!-- Subheading -->
                <p class="text-lg sm:text-xl text-gray-400 font-normal leading-relaxed">
                    Get virtual live Binance balance for marketing and promoting.
                </p>

                <!-- CTAs -->
                <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('checkout.show', ['package' => $package->id]) }}" class="w-full sm:w-auto bg-binance-yellow hover-binance-yellow text-black font-bold px-8 py-3.5 rounded-lg text-base transition-all shadow-lg hover:shadow-yellow-500/25 flex items-center justify-center gap-2">
                        <span>Buy Now</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="#pricing" class="w-full sm:w-auto bg-[#181A20] hover:bg-[#2B313A] text-gray-200 border border-exchange font-semibold px-8 py-3.5 rounded-lg text-base transition-all flex items-center justify-center">
                        View Balance Options
                    </a>
                </div>
            </div>

            <!-- HERO VISUAL: Exchange-Style Portfolio Dashboard Preview -->
            <div id="trading" class="mt-14 max-w-5xl mx-auto">
                <div class="glass-card rounded-2xl p-6 sm:p-8 shadow-2xl border border-exchange relative overflow-hidden">
                    <!-- Dashboard Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-exchange gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold font-mono tracking-wider uppercase px-2 py-0.5 rounded bg-yellow-500/20 text-binance-yellow border border-yellow-500/30">
                                    VIRTUAL BALANCE
                                </span>
                                <span class="text-xs text-gray-500 font-mono">ID: SIM-984210</span>
                            </div>
                            <div class="mt-2 flex items-baseline gap-3">
                                <span class="text-3xl sm:text-4xl font-extrabold text-white font-mono tracking-tight">$100,000.00</span>
                                <span class="text-sm font-semibold text-gray-400">USDT</span>
                            </div>
                        </div>

                        <!-- 24h PNL Widget -->
                        <div class="flex sm:flex-col items-start sm:items-end justify-between sm:justify-center p-3 rounded-lg bg-[#0B0E11] border border-exchange/60">
                            <span class="text-xs text-gray-400 font-medium">24h PNL</span>
                            <div class="flex items-center gap-1.5 text-crypto-green font-bold font-mono text-sm sm:text-base">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                <span>+$2,840.00 (+2.84%)</span>
                            </div>
                        </div>
                    </div>



                    <!-- Mandatory Hero Visual Disclaimer Below Preview -->
                    <div class="mt-6 p-4 rounded-xl bg-[#0B0E11] border border-exchange text-center">
                        <p class="text-xs text-gray-400 font-medium">
                            🔒 <span class="text-gray-300 font-semibold">Virtual funds are not withdrawable or transferable as real cryptocurrency.</span>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- SINGLE PACKAGE PRICING SECTION -->
    <section id="pricing" class="py-16 bg-[#0E1116] border-t border-b border-exchange relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-3xl font-extrabold text-white tracking-tight">Trading Access Pass</h2>
                <p class="mt-3 text-gray-400 text-base">Get unlimited virtual trading balance with instant market pricing engine access.</p>
            </div>

            <!-- Balance Package Card -->
            <div class="max-w-lg mx-auto">
                <div class="glass-card rounded-2xl p-8 border-2 border-binance-yellow/60 relative glow-yellow">
                    <!-- Badge -->
                    <div class="absolute -top-3 right-6 bg-binance-yellow text-black font-extrabold text-[11px] uppercase tracking-wider px-3 py-1 rounded-full shadow">
                        POPULAR ACCESS PASS
                    </div>

                    <!-- Package Header -->
                    <div class="border-b border-exchange pb-6">
                        <h3 class="text-2xl font-bold text-white tracking-wide font-mono">{{ $package->name }}</h3>
                        <p class="mt-1 text-xs text-gray-400">{{ $package->description }}</p>
                        
                        <div class="mt-6 flex items-baseline gap-2">
                            <span class="text-5xl font-extrabold text-white font-mono tracking-tight">${{ number_format($package->price, 0) }}</span>
                            <span class="text-lg font-bold text-binance-yellow font-mono">{{ $package->currency }}</span>
                            <span class="text-xs text-gray-500 font-medium ml-2">/ one-time access</span>
                        </div>
                    </div>

                    <!-- Package Specs -->
                    <div class="py-6 space-y-4 text-sm">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-binance-yellow shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            <div>
                                <span class="font-semibold text-gray-200">Virtual Balance:</span>
                                <span class="text-gray-400 capitalize block text-xs mt-0.5">{{ $package->virtual_balance }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-binance-yellow shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            <div>
                                <span class="font-semibold text-gray-200">Payment Network:</span>
                                <span class="text-gray-400 block text-xs mt-0.5">USDT — TRC20 (Tron Network)</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-binance-yellow shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            <div>
                                <span class="font-semibold text-gray-200">Verification Requirement:</span>
                                <span class="text-gray-400 block text-xs mt-0.5">Manual TRC20 payment review via Telegram before activation.</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-binance-yellow shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            <div>
                                <span class="font-semibold text-gray-200">Trading Tools Included:</span>
                                <span class="text-gray-400 block text-xs mt-0.5">Order Book Interface, Real-time PNL Tracker, Spot & Futures Sandbox.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Buy Now Button -->
                    <div class="pt-4 border-t border-exchange">
                        <a href="{{ route('checkout.show', ['package' => $package->id]) }}" class="w-full bg-binance-yellow hover-binance-yellow text-black font-extrabold py-4 rounded-lg text-center block transition-all shadow-lg hover:shadow-yellow-500/30 text-base">
                            Buy Now ($20 USDT)
                        </a>

                        <p class="text-[11px] text-gray-500 text-center mt-3">
                            Third-party virtual balance. Non-withdrawable & non-transferable.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Interactive Trade Modal Preview -->
    <div x-show="showTradeModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm" x-transition>
        <div @click.away="showTradeModal = false" class="bg-[#181A20] border border-exchange rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-exchange pb-3">
                <h3 class="text-lg font-bold text-white font-mono" x-text="'Trade: ' + tradeAsset + '/USDT'"></h3>
                <button @click="showTradeModal = false" class="text-gray-400 hover:text-white">&times;</button>
            </div>
            
            <div class="p-3 bg-[#0B0E11] rounded border border-exchange text-xs space-y-1">
                <div class="flex justify-between text-gray-400"><span>Market Price:</span><span class="text-white font-mono" x-text="'$' + tradePrice.toLocaleString()"></span></div>
                <div class="flex justify-between text-gray-400"><span>Virtual Balance:</span><span class="text-binance-yellow font-mono">$10,000.00 USDT</span></div>
            </div>

            <div class="space-y-2 text-xs">
                <label class="block text-gray-300 font-semibold">Order Amount</label>
                <input type="number" step="0.01" x-model="amount" class="w-full bg-[#0B0E11] border border-exchange rounded p-2.5 text-white font-mono focus:border-binance-yellow focus:outline-none">
            </div>

            <div class="p-3 rounded bg-yellow-500/10 border border-yellow-500/20 text-[11px] text-yellow-300">
                Notice: Trades in this sandbox use live market liquidity. No actual crypto transactions take place.
            </div>

            <div class="flex gap-3 pt-2">
                <button @click="showTradeModal = false; alert('Buy Order executed successfully in sandbox mode!')" class="flex-1 bg-crypto-green hover:bg-emerald-600 text-black font-bold py-2.5 rounded transition-all">
                    Buy
                </button>
                <button @click="showTradeModal = false; alert('Sell Order executed successfully in sandbox mode!')" class="flex-1 bg-crypto-red hover:bg-rose-600 text-white font-bold py-2.5 rounded transition-all">
                    Sell
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
