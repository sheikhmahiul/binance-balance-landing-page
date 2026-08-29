@extends('layouts.app')

@section('content')
<div class="min-h-screen py-10 bg-[#0B0E11]" x-data="{ selectedOrder: null, showModal: false }">
    <!-- Admin Header -->
    <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="flex items-center justify-between border-b border-exchange pb-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Binance balance" class="w-8 h-8 rounded-full object-contain">
                <div>
                    <h1 class="text-xl font-extrabold font-mono tracking-tight"><span class="text-binance-yellow">Binance</span> <span class="text-white">balance</span> Admin Panel</h1>
                    <p class="text-xs text-gray-400 font-mono">Order Management & Manual TRC20 Verification</p>
                </div>
            </div>

            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs font-mono text-gray-400 hover:text-red-400 px-3 py-1.5 rounded bg-[#181A20] border border-exchange transition-colors">
                    Logout Admin
                </button>
            </form>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Metrics Bar -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="glass-card p-5 rounded-xl border border-exchange space-y-1">
                <span class="text-xs text-gray-400 font-mono uppercase">Total Orders</span>
                <div class="text-2xl font-extrabold text-white font-mono">{{ $totalOrders }}</div>
            </div>

            <div class="glass-card p-5 rounded-xl border border-exchange space-y-1">
                <span class="text-xs text-yellow-400 font-mono uppercase">Awaiting / Under Review</span>
                <div class="text-2xl font-extrabold text-binance-yellow font-mono">{{ $pendingCount }}</div>
            </div>

            <div class="glass-card p-5 rounded-xl border border-exchange space-y-1">
                <span class="text-xs text-crypto-green font-mono uppercase">Approved Passes</span>
                <div class="text-2xl font-extrabold text-crypto-green font-mono">{{ $approvedCount }}</div>
            </div>

            <div class="glass-card p-5 rounded-xl border border-exchange space-y-1">
                <span class="text-xs text-gray-400 font-mono uppercase">Total Approved Volume</span>
                <div class="text-2xl font-extrabold text-white font-mono">${{ number_format($totalRevenue, 2) }} USDT</div>
            </div>
        </div>

        <!-- System Configuration Preview Banner -->
        <div class="glass-card p-4 rounded-xl border border-exchange text-xs font-mono flex flex-col sm:flex-row justify-between gap-4 bg-[#181A20]">
            <div class="space-y-1">
                <span class="text-gray-400 uppercase font-semibold">TRC20 Wallet Address (from .env):</span>
                <div class="text-binance-yellow font-bold break-all">{{ $trc20Address }}</div>
            </div>
            <div class="space-y-1 text-left sm:text-right">
                <span class="text-gray-400 uppercase font-semibold">Telegram Channel:</span>
                <div class="text-cyan-400 font-bold">@{{ $telegramHandle }}</div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="glass-card p-6 rounded-2xl border border-exchange space-y-4">
            <form method="GET" action="{{ route('admin.index') }}" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Order #, Customer Name, Email, or Telegram handle..." class="w-full bg-[#0B0E11] border border-exchange rounded-lg px-4 py-2.5 text-xs text-white font-mono focus:border-binance-yellow focus:outline-none">
                </div>

                <div class="flex gap-2">
                    <select name="status" class="bg-[#0B0E11] border border-exchange rounded-lg px-3 py-2.5 text-xs text-white font-mono focus:border-binance-yellow focus:outline-none">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="waiting" {{ request('status') == 'waiting' ? 'selected' : '' }}>Waiting</option>
                        <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>

                    <button type="submit" class="bg-binance-yellow hover-binance-yellow text-black font-bold px-5 py-2.5 rounded-lg text-xs font-mono transition-all">
                        Filter Orders
                    </button>
                    
                    @if(request('search') || request('status'))
                        <a href="{{ route('admin.index') }}" class="bg-exchange-hover text-gray-300 font-bold px-3 py-2.5 rounded-lg text-xs font-mono flex items-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            <!-- Orders Table -->
            <div class="overflow-x-auto pt-2">
                <table class="w-full text-left font-mono text-xs">
                    <thead>
                        <tr class="text-gray-500 border-b border-exchange uppercase text-[11px]">
                            <th class="pb-3 font-semibold">Order #</th>
                            <th class="pb-3 font-semibold">Customer</th>
                            <th class="pb-3 font-semibold">Telegram</th>
                            <th class="pb-3 font-semibold">Package</th>
                            <th class="pb-3 font-semibold">Amount</th>
                            <th class="pb-3 font-semibold">Status</th>
                            <th class="pb-3 font-semibold">Created</th>
                            <th class="pb-3 font-semibold text-right">Manage</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-exchange/50">
                        @forelse($orders as $order)
                            <tr class="hover:bg-exchange-hover/40 transition-colors">
                                <td class="py-3.5 font-bold text-white">
                                    <a href="{{ route('order.status', ['order_number' => $order->order_number]) }}" target="_blank" class="hover:underline text-binance-yellow">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="py-3.5">
                                    <div class="text-white font-semibold">{{ $order->full_name }}</div>
                                    <div class="text-[10px] text-gray-500">{{ $order->email }}</div>
                                </td>
                                <td class="py-3.5 text-cyan-400">
                                    <a href="https://t.me/{{ $order->telegram_username }}" target="_blank" class="hover:underline">
                                        @{{ $order->telegram_username }}
                                    </a>
                                </td>
                                <td class="py-3.5 text-gray-300">{{ $order->package->name ?? 'ACCESS PASS' }}</td>
                                <td class="py-3.5 font-bold text-white">${{ number_format($order->amount, 2) }} USDT</td>
                                <td class="py-3.5">
                                    <span class="px-2.5 py-1 rounded text-[10px] font-bold uppercase
                                        @if($order->verification_status === 'approved') bg-crypto-green/20 text-crypto-green border border-crypto-green/30
                                        @elseif($order->verification_status === 'rejected') bg-crypto-red/20 text-crypto-red border border-crypto-red/30
                                        @else bg-yellow-500/20 text-binance-yellow border border-yellow-500/30
                                        @endif">
                                        {{ $order->formatted_status }}
                                    </span>
                                </td>
                                <td class="py-3.5 text-gray-500 text-[11px]">{{ $order->created_at->format('M d, H:i') }}</td>
                                <td class="py-3.5 text-right">
                                    <button @click="selectedOrder = {{ json_encode($order) }}; showModal = true" class="bg-exchange-hover hover:bg-binance-yellow hover:text-black text-gray-200 px-3 py-1.5 rounded text-xs font-bold transition-all">
                                        Review / Update
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-gray-500">
                                    No orders found matching your criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="pt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

    <!-- Review & Status Update Modal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm" x-transition>
        <div @click.away="showModal = false" class="bg-[#181A20] border border-exchange rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-5">
            <div class="flex items-center justify-between border-b border-exchange pb-3">
                <h3 class="text-base font-bold text-white font-mono" x-text="'Update Order #' + (selectedOrder ? selectedOrder.order_number : '')"></h3>
                <button @click="showModal = false" class="text-gray-400 hover:text-white">&times;</button>
            </div>

            <template x-if="selectedOrder">
                <form :action="'{{ url('/admin/orders') }}/' + selectedOrder.id + '/status'" method="POST" class="space-y-4 text-xs font-mono">
                    @csrf

                    <!-- Customer Detail Summary -->
                    <div class="p-3 bg-[#0B0E11] rounded-lg border border-exchange space-y-1.5 text-gray-300">
                        <div><span class="text-gray-500">Customer:</span> <strong class="text-white" x-text="selectedOrder.full_name"></strong> (<span x-text="selectedOrder.email"></span>)</div>
                        <div><span class="text-gray-500">Telegram:</span> <strong class="text-cyan-400" x-text="'@' + selectedOrder.telegram_username"></strong></div>
                        <div><span class="text-gray-500">Amount:</span> <strong class="text-binance-yellow" x-text="'$' + selectedOrder.amount + ' USDT (TRC20)'"></strong></div>
                        <template x-if="selectedOrder.transaction_ref">
                            <div class="break-all"><span class="text-gray-500">TXID:</span> <span class="text-gray-300" x-text="selectedOrder.transaction_ref"></span></div>
                        </template>
                    </div>

                    <!-- Verification Status Selection -->
                    <div>
                        <label class="block text-gray-300 font-semibold mb-1">Verification Status</label>
                        <select name="verification_status" x-model="selectedOrder.verification_status" class="w-full bg-[#0B0E11] border border-exchange rounded p-2.5 text-white font-mono focus:border-binance-yellow focus:outline-none">
                            <option value="waiting">waiting (Waiting Customer)</option>
                            <option value="under_review">under_review (Under Review)</option>
                            <option value="approved">approved (Approved Access Pass)</option>
                            <option value="rejected">rejected (Rejected Payment)</option>
                        </select>
                    </div>

                    <!-- Payment Status Selection -->
                    <div>
                        <label class="block text-gray-300 font-semibold mb-1">Payment Status</label>
                        <select name="payment_status" x-model="selectedOrder.payment_status" class="w-full bg-[#0B0E11] border border-exchange rounded p-2.5 text-white font-mono focus:border-binance-yellow focus:outline-none">
                            <option value="pending">pending</option>
                            <option value="submitted">submitted</option>
                            <option value="verified">verified</option>
                            <option value="rejected">rejected</option>
                        </select>
                    </div>

                    <!-- Transaction TXID input -->
                    <div>
                        <label class="block text-gray-300 font-semibold mb-1">Transaction Ref / TXID (Optional)</label>
                        <input type="text" name="transaction_ref" x-model="selectedOrder.transaction_ref" placeholder="Enter TRC20 transaction hash..." class="w-full bg-[#0B0E11] border border-exchange rounded p-2.5 text-white font-mono focus:border-binance-yellow focus:outline-none">
                    </div>

                    <div class="flex gap-3 pt-3 border-t border-exchange">
                        <button type="button" @click="showModal = false" class="flex-1 bg-exchange-hover text-gray-300 font-bold py-2.5 rounded">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 bg-binance-yellow hover-binance-yellow text-black font-bold py-2.5 rounded transition-all">
                            Save Changes
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </div>
</div>
@endsection
