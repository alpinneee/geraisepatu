@extends('layouts.customer')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Keranjang Belanja</h1>

        @if(session('success'))
            <div class="mt-4 rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mt-4 rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-8">
            @if($cartItems->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">Keranjang Belanja Kosong</h3>
                    <p class="mt-1 text-sm text-gray-500">Belum ada produk di keranjang belanja Anda.</p>
                    <div class="mt-6">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Lanjutkan Belanja
                        </a>
                    </div>
                </div>
            @else
                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Cart Items -->
                    <div class="flex-1">
                        <div class="space-y-4">
                            @foreach($cartItems as $item)
                                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm" id="cart-item-{{ $item->product->id }}-{{ $item->size ?? 'no-size' }}">
                                    <div class="flex flex-col sm:flex-row gap-4">
                                        <!-- Product Image -->
                                        <div class="w-full sm:w-24 h-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                            @if($item->product->images->isNotEmpty())
                                                <img src="{{ asset('storage/' . $item->product->images->where('is_primary', true)->first()->image_path) }}" 
                                                    alt="{{ $item->product->name }}" 
                                                    class="h-full w-full object-cover object-center">
                                            @else
                                                <div class="flex h-full w-full items-center justify-center bg-gray-100">
                                                    <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Product Info -->
                                        <div class="flex-1 space-y-3">
                                            <!-- Product Name & Price -->
                                            <div class="flex flex-col sm:flex-row sm:justify-between">
                                                <div class="flex-1">
                                                    <h3 class="text-base font-medium text-gray-900">
                                                        <a href="{{ route('products.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                                    </h3>
                                                    <p class="text-sm text-gray-500">{{ $item->product->category->name }}</p>
                                                    @if($item->size)
                                                        <p class="text-sm text-gray-500">Ukuran: {{ $item->size }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-right mt-2 sm:mt-0">
                                                    @if($item->product->discount_price)
                                                        <p class="text-base font-medium text-gray-900">Rp {{ number_format($item->product->discount_price, 0, ',', '.') }}</p>
                                                        <p class="text-sm text-gray-500 line-through">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                                    @else
                                                        <p class="text-base font-medium text-gray-900">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Quantity Controls -->
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm text-gray-500">Jumlah:</span>
                                                    <div class="flex rounded-md shadow-sm">
                                                        <button type="button" 
                                                            class="decrement-quantity px-3 py-1 text-sm border border-gray-300 rounded-l-md bg-gray-50 hover:bg-gray-100"
                                                            data-product-id="{{ $item->product->id }}"
                                                            data-size="{{ $item->size ?? '' }}">
                                                            -
                                                        </button>
                                                        <input type="number" 
                                                            id="quantity-{{ $item->product->id }}-{{ $item->size ?? 'no-size' }}" 
                                                            min="1" max="{{ $item->product->stock }}" 
                                                            value="{{ $item->quantity }}" 
                                                            class="update-quantity w-16 px-2 py-1 text-center text-sm border-t border-b border-gray-300"
                                                            data-product-id="{{ $item->product->id }}"
                                                            data-size="{{ $item->size ?? '' }}">
                                                        <button type="button" 
                                                            class="increment-quantity px-3 py-1 text-sm border border-gray-300 rounded-r-md bg-gray-50 hover:bg-gray-100"
                                                            data-product-id="{{ $item->product->id }}"
                                                            data-size="{{ $item->size ?? '' }}"
                                                            data-max-stock="{{ $item->product->stock }}">
                                                            +
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                <div class="text-right">
                                                    <p class="text-sm text-gray-500">Subtotal:</p>
                                                    <p class="text-base font-medium text-gray-900" id="subtotal-{{ $item->product->id }}-{{ $item->size ?? 'no-size' }}">
                                                        Rp {{ number_format($item->product->discount_price ? $item->product->discount_price * $item->quantity : $item->product->price * $item->quantity, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 flex justify-between">
                            <a href="{{ route('products.index') }}" class="flex items-center text-sm font-medium text-blue-600 hover:text-blue-500">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Lanjutkan Belanja
                            </a>
                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-500">
                                    Kosongkan Keranjang
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="w-full lg:w-80 lg:flex-shrink-0">
                        <div class="sticky top-4 rounded-lg bg-gray-50 p-4 lg:p-6">
                            <h2 class="text-lg font-medium text-gray-900">Ringkasan Pesanan</h2>
                            
                            <div class="mt-4 space-y-3">
                                <div class="flex items-center justify-between border-t border-gray-200 pt-3">
                                    <dt class="text-sm text-gray-600">Subtotal</dt>
                                    <dd class="text-sm font-medium text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</dd>
                                </div>
                                @if($discount > 0)
                                    <div class="flex items-center justify-between">
                                        <dt class="text-sm text-gray-600">Diskon</dt>
                                        <dd class="text-sm font-medium text-green-600">-Rp {{ number_format($discount, 0, ',', '.') }}</dd>
                                    </div>
                                @endif
                                <div class="flex items-center justify-between border-t border-gray-200 pt-3">
                                    <dt class="text-base font-medium text-gray-900">Total</dt>
                                    <dd class="text-base font-medium text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</dd>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('checkout.index') }}" class="block w-full rounded-md bg-blue-600 px-4 py-3 text-center text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                                    Lanjutkan ke Pembayaran
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Increment quantity
        document.querySelectorAll('.increment-quantity').forEach(function(button) {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const size = this.getAttribute('data-size');
                const maxStock = parseInt(this.getAttribute('data-max-stock'));
                const inputId = 'quantity-' + productId + (size ? '-' + size : '-no-size');
                const input = document.getElementById(inputId);
                const currentValue = parseInt(input.value);
                
                if (currentValue < maxStock) {
                    input.value = currentValue + 1;
                    updateCartItem(productId, currentValue + 1, size);
                }
            });
        });
        
        // Decrement quantity
        document.querySelectorAll('.decrement-quantity').forEach(function(button) {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const size = this.getAttribute('data-size');
                const inputId = 'quantity-' + productId + (size ? '-' + size : '-no-size');
                const input = document.getElementById(inputId);
                const currentValue = parseInt(input.value);
                
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                    updateCartItem(productId, currentValue - 1, size);
                }
            });
        });
        
        // Update quantity on input change
        document.querySelectorAll('.update-quantity').forEach(function(input) {
            input.addEventListener('change', function() {
                const productId = this.getAttribute('data-product-id');
                const size = this.getAttribute('data-size');
                const value = parseInt(this.value);
                
                if (value < 1) {
                    this.value = 1;
                    updateCartItem(productId, 1, size);
                } else {
                    updateCartItem(productId, value, size);
                }
            });
        });
        
        // Remove item
        document.querySelectorAll('.remove-item').forEach(function(button) {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const size = this.getAttribute('data-size');
                removeCartItem(productId, size);
            });
        });
        
        // Update cart item
        function updateCartItem(productId, quantity, size) {
            const requestData = {
                product_id: productId,
                quantity: quantity
            };
            
            if (size) {
                requestData.size = size;
            }
            
            fetch('{{ route('cart.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update subtotal
                    const subtotalId = 'subtotal-' + productId + (size ? '-' + size : '-no-size');
                    const subtotalElement = document.getElementById(subtotalId);
                    if (subtotalElement) {
                        subtotalElement.textContent = data.formatted_subtotal;
                    }
                    
                    // Reload page to update totals
                    window.location.reload();
                } else {
                    alert(data.message || 'Gagal memperbarui keranjang');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memperbarui keranjang');
            });
        }
        
        // Remove cart item
        function removeCartItem(productId, size) {
            if (confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
                fetch('{{ route('cart.remove') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload page to update totals
                        window.location.reload();
                    } else {
                        alert(data.message || 'Gagal menghapus item dari keranjang');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus item dari keranjang');
                });
            }
        }
    });
</script>
@endpush 