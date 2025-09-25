@extends('layouts.customer')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Success Header -->
        <div class="text-center mb-8">
            <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Pembayaran Berhasil!</h1>
            <p class="text-gray-600">Terima kasih telah berbelanja di Gerai Sepatu</p>
        </div>

        <!-- Order Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Order Information -->
            <div>
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Informasi Pesanan</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nomor Pesanan</span>
                        <span class="font-medium">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Pesanan</span>
                        <span class="font-medium">{{ $order->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status</span>
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                            {{ $order->status_label }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Metode Pembayaran</span>
                        <span class="font-medium">{{ $order->payment_method_label }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status Pembayaran</span>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                            {{ $order->payment_status_label }}
                        </span>
                    </div>
                    @if($order->shipping_expedition_name)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ekspedisi</span>
                        <span class="font-medium">{{ $order->shipping_expedition_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Estimasi</span>
                        <span class="font-medium">{{ $order->shipping_estimation }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Shipping Address -->
            <div>
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Alamat Pengiriman</h2>
                @php
                    $shippingAddress = json_decode($order->shipping_address, true);
                @endphp
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="space-y-1">
                        <p class="font-medium">{{ $shippingAddress['name'] ?? 'N/A' }}</p>
                        <p class="text-gray-600">{{ $shippingAddress['phone'] ?? 'N/A' }}</p>
                        <p class="text-gray-600">{{ $shippingAddress['address'] ?? 'N/A' }}</p>
                        <p class="text-gray-600">{{ $shippingAddress['city'] ?? 'N/A' }}, {{ $shippingAddress['province'] ?? 'N/A' }} {{ $shippingAddress['postal_code'] ?? 'N/A' }}</p>
                    </div>
                </div>
                
                @if($order->notes)
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Catatan</h3>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Items -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4 text-gray-900">Detail Pesanan</h2>
            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                    <img src="{{ $item->product->images->first()?->image_url ?? '/images/placeholder.jpg' }}" 
                         alt="{{ $item->product->name }}" 
                         class="w-16 h-16 object-cover rounded">
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                        <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-600">
                            Total: Rp {{ number_format($item->total, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8 animate-slide-up animation-delay-4000">
            <h2 class="text-xl font-semibold mb-4 text-gray-900">Ringkasan Pembayaran</h2>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-medium">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                
                @if($order->discount_amount > 0)
                <div class="flex justify-between text-green-600">
                    <span>Diskon</span>
                    <span>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Ongkos Kirim</span>
                    <span class="font-medium">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                
                @if($order->cod_fee > 0)
                <div class="flex justify-between text-orange-600">
                    <span>Biaya COD</span>
                    <span>Rp {{ number_format($order->cod_fee, 0, ',', '.') }}</span>
                </div>
                @endif
                
                <hr class="my-3">
                
                <div class="flex justify-between text-lg font-bold">
                    <span>Total</span>
                    <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Midtrans Invoice -->
        @if($order->payment_method === 'midtrans' && isset($transactionDetails))
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8 animate-slide-up animation-delay-4000">
            <h2 class="text-xl font-semibold mb-4 text-blue-900 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6z"/>
                    <path d="M6 8h8v2H6V8zm0 4h4v2H6v-2z"/>
                </svg>
                Invoice Midtrans
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    @if(isset($transactionDetails->transaction_id))
                    <div class="flex justify-between">
                        <span class="text-blue-700 font-medium">Transaction ID</span>
                        <span class="font-mono text-sm bg-white px-2 py-1 rounded">{{ $transactionDetails->transaction_id }}</span>
                    </div>
                    @endif
                    
                    @if(isset($transactionDetails->payment_type))
                    <div class="flex justify-between">
                        <span class="text-blue-700 font-medium">Payment Type</span>
                        <span class="font-medium">{{ strtoupper($transactionDetails->payment_type) }}</span>
                    </div>
                    @endif
                    
                    @if(isset($transactionDetails->transaction_time))
                    <div class="flex justify-between">
                        <span class="text-blue-700 font-medium">Transaction Time</span>
                        <span class="font-medium">{{ date('d M Y H:i', strtotime($transactionDetails->transaction_time)) }}</span>
                    </div>
                    @endif
                </div>
                
                <div class="space-y-3">
                    @if(isset($transactionDetails->transaction_status))
                    <div class="flex justify-between">
                        <span class="text-blue-700 font-medium">Status</span>
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            @if($transactionDetails->transaction_status === 'settlement' || $transactionDetails->transaction_status === 'capture')
                                bg-green-100 text-green-800
                            @elseif($transactionDetails->transaction_status === 'pending')
                                bg-yellow-100 text-yellow-800
                            @else
                                bg-red-100 text-red-800
                            @endif">
                            {{ strtoupper($transactionDetails->transaction_status) }}
                        </span>
                    </div>
                    @endif
                    
                    @if(isset($transactionDetails->gross_amount))
                    <div class="flex justify-between">
                        <span class="text-blue-700 font-medium">Amount</span>
                        <span class="font-bold">{{ $transactionDetails->currency ?? 'IDR' }} {{ number_format($transactionDetails->gross_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    
                    @if(isset($transactionDetails->fraud_status) && $transactionDetails->fraud_status !== 'accept')
                    <div class="flex justify-between">
                        <span class="text-blue-700 font-medium">Fraud Status</span>
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            {{ strtoupper($transactionDetails->fraud_status) }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="mt-4 pt-4 border-t border-blue-200">
                <p class="text-xs text-blue-600">
                    <strong>Note:</strong> This is your official payment receipt from Midtrans. Keep this for your records.
                </p>
            </div>
        </div>
        @endif

       

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 mt-8">
            <a href="{{ route('profile.orders.show', $order) }}" 
               class="flex-1 bg-green-600 text-white py-3 px-6 rounded-lg font-medium text-center hover:bg-green-700 transition duration-200">
                Lihat Detail Pesanan
            </a>
            
            @if($order->payment_method === 'midtrans' && isset($transactionDetails))
            <button onclick="window.print()" 
                    class="flex-1 bg-purple-600 text-white py-3 px-6 rounded-lg font-medium text-center hover:bg-purple-700 transition duration-200">
                Print Invoice
            </button>
            @endif
            
            <a href="{{ route('home') }}" 
               class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg font-medium text-center hover:bg-blue-700 transition duration-200">
                Lanjutkan Belanja
            </a>
            
            @auth
            <a href="{{ route('profile.orders') }}" 
               class="flex-1 bg-gray-100 text-gray-700 py-3 px-6 rounded-lg font-medium text-center hover:bg-gray-200 transition duration-200">
                Lihat Pesanan Saya
            </a>
            @endif
        </div>
    </div>
</div>
@endsection 