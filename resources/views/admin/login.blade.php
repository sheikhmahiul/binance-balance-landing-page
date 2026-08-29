@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full glass-card p-8 rounded-2xl border border-exchange space-y-6">
        <div class="text-center">
            <img src="{{ asset('images/logo.png') }}" alt="Binance balance" class="w-12 h-12 rounded-full object-contain mx-auto mb-3">
            <h2 class="text-2xl font-extrabold text-white font-mono tracking-tight"><span class="text-binance-yellow">Binance</span> balance Admin</h2>
            <p class="text-xs text-gray-400 mt-1">Order Review & TRC20 Verification Portal</p>
        </div>

        @if ($errors->any())
            <div class="p-3 rounded-lg bg-red-950/80 border border-red-500/50 text-red-300 text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="password" class="block text-xs font-semibold text-gray-300 uppercase tracking-wider font-mono mb-2">Admin Passcode</label>
                <input type="password" name="password" id="password" required placeholder="Enter admin password..." class="w-full bg-[#0B0E11] border border-exchange rounded-lg px-4 py-3 text-white text-sm focus:border-binance-yellow focus:outline-none font-mono">
            </div>

            <button type="submit" class="w-full bg-binance-yellow hover-binance-yellow text-black font-bold py-3.5 rounded-lg text-sm transition-all shadow-lg">
                Authenticate Admin Access
            </button>
        </form>
    </div>
</div>
@endsection
