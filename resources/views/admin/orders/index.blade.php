@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="space-y-3">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-lg font-semibold text-gray-900">Orders</h1>
        <a href="{{ route('admin.orders.export', request()->all()) }}" 
           class="px-2 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">
            Export
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-3 py-2 rounded text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-4 gap-2">
        <div class="bg-white border rounded p-2">
            <div class="text-lg font-bold text-blue-600">{{ $orders->total() }}</div>
            <div class="text-xs text-gray-500">Total</div>
        </div>
        <div class="bg-white border rounded p-2">
            <div class="text-lg font-bold text-yellow-600">{{ $orders->where('payment_status', 'pending')->count() }}</div>
            <div class="text-xs text-gray-500">Pending</div>
        </div>
        <div class="bg-white border rounded p-2">
            <div class="text-lg font-bold text-green-600">{{ $orders->where('payment_status', 'paid')->count() }}</div>
            <div class="text-xs text-gray-500">Paid</div>
        </div>
        <div class="bg-white border rounded p-2">
            <div class="text-lg font-bold text-purple-600">{{ $orders->where('status', 'delivered')->count() }}</div>
            <div class="text-xs text-gray-500">Delivered</div>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" class="bg-white border rounded p-2">
        <div class="flex flex-wrap gap-2 items-center">
            <select name="status" class="border rounded px-2 py-1 text-xs">
                <option value="all">All Status</option>
                @foreach($orderStatuses as $key => $label)
                    <option value="{{ $key }}" {{ request('status', 'all') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="payment_status" class="border rounded px-2 py-1 text-xs">
                <option value="all">All Payment</option>
                @foreach($paymentStatuses as $key => $label)
                    <option value="{{ $key }}" {{ request('payment_status', 'all') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="border rounded px-2 py-1 text-xs">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="border rounded px-2 py-1 text-xs">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="border rounded px-2 py-1 text-xs">
            <button type="submit" class="px-2 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700">Filter</button>
        </div>
    </form>

    <!-- Orders Table -->
    <div class="bg-white border rounded overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-2 py-1 text-left font-medium text-gray-600">Order</th>
                        <th class="px-2 py-1 text-left font-medium text-gray-600">Customer</th>
                        <th class="px-2 py-1 text-left font-medium text-gray-600">Payment</th>
                        <th class="px-2 py-1 text-left font-medium text-gray-600">Status</th>
                        <th class="px-2 py-1 text-left font-medium text-gray-600">Total</th>
                        <th class="px-2 py-1 text-center font-medium text-gray-600">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-2 py-2">
                            <div class="font-medium">#{{ $order->order_number }}</div>
                            <div class="text-gray-500">{{ $order->created_at->format('d M Y') }}</div>
                        </td>
                        <td class="px-2 py-2">
                            <div class="font-medium">{{ $order->user->name ?? 'Guest' }}</div>
                            <div class="text-gray-500">{{ substr($order->user->email ?? json_decode($order->shipping_address)->email, 0, 20) }}{{ strlen($order->user->email ?? json_decode($order->shipping_address)->email) > 20 ? '...' : '' }}</div>
                        </td>
                        <td class="px-2 py-2">
                            <span class="px-1 py-0.5 rounded text-xs
                                @if($order->payment_status == 'paid') bg-green-100 text-green-700
                                @elseif($order->payment_status == 'pending') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ $order->payment_status_label }}
                            </span>
                        </td>
                        <td class="px-2 py-2">
                            <span class="px-1 py-0.5 rounded text-xs
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-700
                                @elseif($order->status == 'processing') bg-blue-100 text-blue-700
                                @elseif($order->status == 'shipped') bg-purple-100 text-purple-700
                                @elseif($order->status == 'delivered') bg-green-100 text-green-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-2 py-2 font-medium">Rp {{ number_format($order->total_amount) }}</td>
                        <td class="px-2 py-2 text-center">
                            <div class="flex justify-center gap-1">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="bg-blue-600 text-white px-2 py-1 rounded text-xs hover:bg-blue-700">
                                    View
                                </a>
                                @if($order->payment_status === 'pending')
                                <form action="{{ route('admin.orders.confirm-payment', $order) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="bg-green-600 text-white px-2 py-1 rounded text-xs hover:bg-green-700"
                                            onclick="return confirm('Confirm payment?')">
                                        Confirm
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            No orders found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($orders->hasPages())
    <div class="flex items-center justify-between text-xs text-gray-600">
        <div>{{ $orders->firstItem() ?? 0 }}-{{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }}</div>
        <div>{{ $orders->withQueryString()->links() }}</div>
    </div>
    @endif
</div>
@endsection