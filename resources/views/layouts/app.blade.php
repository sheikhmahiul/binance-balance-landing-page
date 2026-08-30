<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Binance balance | Virtual Crypto Trading Simulation</title>
    <meta name="description" content="Get virtual live Binance balance for marketing and promoting.">


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN Fallback -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'binance-yellow': '#F0B90D',
                        'crypto-green': '#0ECB81',
                        'crypto-red': '#F6465D',
                        'exchange-dark': '#0B0E11',
                        'exchange-card': '#181A20',
                        'exchange-hover': '#2B313A',
                        'exchange-border': '#2B313A'
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js for dynamic interactive states -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind / Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <style>
        svg {
            display: inline-block;
            vertical-align: middle;
        }
        svg:not([class*="w-"]) {
            width: 1.25rem;
            height: 1.25rem;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #0B0E11;
            color: #EAECF0;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        .bg-exchange-dark {
            background-color: #0B0E11;
        }
        .bg-exchange-card {
            background-color: #181A20;
        }
        .bg-exchange-hover {
            background-color: #2B313A;
        }
        .border-exchange {
            border-color: #2B313A;
        }
        .text-binance-yellow {
            color: #F0B90D;
        }
        .bg-binance-yellow {
            background-color: #F0B90D;
        }
        .hover-binance-yellow:hover {
            background-color: #FCD535;
        }
        .text-crypto-green {
            color: #0ECB81;
        }
        .text-crypto-red {
            color: #F6465D;
        }
        .glow-yellow {
            box-shadow: 0 0 25px rgba(240, 185, 13, 0.15);
        }
        .glass-card {
            background: rgba(24, 26, 32, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(43, 49, 58, 0.8);
        }
    </style>
</head>
<body class="bg-exchange-dark text-gray-100 min-h-screen flex flex-col antialiased selection:bg-yellow-500 selection:text-black">

    <!-- Flash Messages -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="fixed top-4 right-4 z-50 bg-green-900/90 border border-green-500 text-green-200 px-5 py-3 rounded-lg shadow-xl flex items-center gap-3 backdrop-blur-md">
            <svg class="w-5 h-5 text-crypto-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="fixed top-4 right-4 z-50 bg-red-900/90 border border-red-500 text-red-200 px-5 py-3 rounded-lg shadow-xl flex items-center gap-3 backdrop-blur-md">
            <svg class="w-5 h-5 text-crypto-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Content Yield -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Essential Independent Compliance Banner Footer -->
    <footer class="border-t border-exchange py-6 bg-[#0B0E11] text-xs text-gray-500">
        <div class="max-w-7xl mx-mx px-4 sm:px-6 lg:px-8 mx-auto text-center space-y-2">
            <p class="font-medium text-gray-400 flex items-center justify-center gap-2"><img src="{{ asset('images/logo.png') }}" alt="Binance balance" class="w-5 h-5 rounded-full object-contain"><span class="text-binance-yellow font-bold">Binance</span> <span class="text-white font-bold">balance</span></p>
            <p class="max-w-3xl mx-auto text-gray-500 leading-relaxed">
                Get virtual live Binance balance for marketing and promoting.
            </p>
        </div>
    </footer>

</body>
</html>
