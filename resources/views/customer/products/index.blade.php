@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-xs font-medium text-gray-700 hover:text-gray-900">
                        <svg class="w-3 h-3 mr-1.5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
                        Beranda
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-xs font-medium text-gray-500 md:ml-1">Produk</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="mt-4">
            <h1 class="text-xl font-semibold tracking-tight text-gray-900">Semua Produk</h1>
        </div>

        <!-- Mobile Filter Toggle -->
        <div class="mt-4 lg:hidden">
            <button id="mobile-filter-toggle" class="w-full flex items-center justify-between bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                <span>Filter & Urutkan</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Filter Sidebar -->
            <div class="lg:col-span-1">
                <div id="mobile-filter-panel" class="hidden lg:block bg-white p-3 rounded-md border border-gray-200">
                    <h2 class="text-base font-semibold text-gray-900 mb-3">Filter</h2>
                    <form action="{{ route('products.index') }}" method="GET">
                        <!-- Category Filter -->
                        <div class="mb-4">
                            <h3 class="text-xs font-medium text-gray-900 mb-1">Kategori</h3>
                            <div class="space-y-1">
                                @foreach($categories as $category)
                                    <div class="flex items-center">
                                        <input 
                                            id="category-{{ $category->id }}" 
                                            name="category" 
                                            value="{{ $category->id }}" 
                                            type="radio" 
                                            class="h-3 w-3 text-gray-900 focus:ring-gray-700 border-gray-300"
                                            {{ request('category') == $category->id ? 'checked' : '' }}
                                        >
                                        <label for="category-{{ $category->id }}" class="ml-2 text-xs text-gray-700">
                                            {{ $category->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- Price Range Filter -->
                        <div class="mb-4">
                            <h3 class="text-xs font-medium text-gray-900 mb-1">Harga</h3>
                            <div class="grid grid-cols-2 gap-1">
                                <div>
                                    <input 
                                        type="number" 
                                        id="min_price" 
                                        name="min_price" 
                                        placeholder="Min" 
                                        class="block w-full rounded border-gray-200 text-xs focus:border-gray-700 focus:ring-gray-700"
                                        value="{{ request('min_price') }}"
                                        min="{{ $minPrice ?? 0 }}"
                                    >
                                </div>
                                <div>
                                    <input 
                                        type="number" 
                                        id="max_price" 
                                        name="max_price" 
                                        placeholder="Max" 
                                        class="block w-full rounded border-gray-200 text-xs focus:border-gray-700 focus:ring-gray-700"
                                        value="{{ request('max_price') }}"
                                        max="{{ $maxPrice ?? 999999999 }}"
                                    >
                                </div>
                            </div>
                        </div>
                        <!-- Sort Filter -->
                        <div class="mb-4">
                            <h3 class="text-xs font-medium text-gray-900 mb-1">Urutkan</h3>
                            <select 
                                name="sort" 
                                class="block w-full rounded border-gray-200 text-xs focus:border-gray-700 focus:ring-gray-700"
                            >
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama: A-Z</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama: Z-A</option>
                            </select>
                        </div>
                        <!-- Search -->
                        <div class="mb-4">
                            <h3 class="text-xs font-medium text-gray-900 mb-1">Cari</h3>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    name="search" 
                                    placeholder="Cari produk..." 
                                    class="block w-full rounded border-gray-200 text-xs focus:border-gray-700 focus:ring-gray-700"
                                    value="{{ request('search') }}"
                                >
                            </div>
                        </div>
                        <div class="flex space-x-1">
                            <button 
                                type="submit" 
                                class="flex-1 rounded bg-gray-900 px-2 py-1 text-xs font-semibold text-white hover:bg-gray-700 transition"
                            >
                                Terapkan
                            </button>
                            <a 
                                href="{{ route('products.index') }}" 
                                class="flex-1 rounded bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-900 hover:bg-gray-200 transition"
                            >
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Product Grid -->
            <div class="lg:col-span-3">
                @if($products->isEmpty())
                    <div class="py-8 text-center">
                        <p class="text-gray-400 text-sm">Tidak ada produk yang ditemukan.</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-x-3 gap-y-4 sm:gap-x-4 sm:gap-y-6 lg:grid-cols-3 xl:gap-x-6">
                        @foreach($products as $product)
                            <div class="group relative border border-gray-200 rounded-lg p-2 sm:p-3 bg-white hover:shadow-md transition">
                                <div class="aspect-square w-full overflow-hidden rounded bg-gray-100 group-hover:opacity-80 flex items-center justify-center relative">
                                    @php
                                        $primaryImage = $product->images->where('is_primary', true)->first();
                                    @endphp
                                    @if($primaryImage && $primaryImage->image_path)
                                        <img src="{{ asset('storage/' . $primaryImage->image_path) }}" 
                                            alt="{{ $product->name }}" 
                                            class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                                    @else
                                        <div class="flex h-full items-center justify-center bg-gray-100">
                                            <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <!-- Wishlist Button -->
                                    <div class="absolute top-2 left-2 z-10">
                                        <x-wishlist-button :product-id="$product->id" size="sm" />
                                    </div>
                                    
                                    @if($product->discount_price)
                                        <div class="absolute top-2 right-2 bg-gray-900 text-white px-2 py-0.5 text-[10px] font-bold rounded">
                                            -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-2 space-y-1">
                                    <h3 class="text-xs sm:text-sm font-medium text-gray-800 line-clamp-2 leading-tight">
                                        <a href="{{ route('products.show', $product->slug) }}">
                                            <span aria-hidden="true" class="absolute inset-0"></span>
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    <p class="text-[10px] sm:text-xs text-gray-500">{{ $product->category->name }}</p>
                                    <div class="flex items-center justify-between">
                                        @if($product->discount_price)
                                            <div>
                                                <span class="text-xs sm:text-sm font-bold text-gray-900">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</span>
                                                <div class="text-[9px] sm:text-[10px] text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                            </div>
                                        @else
                                            <span class="text-xs sm:text-sm font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $products->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterToggle = document.getElementById('mobile-filter-toggle');
    const filterPanel = document.getElementById('mobile-filter-panel');
    
    if (filterToggle && filterPanel) {
        filterToggle.addEventListener('click', function() {
            filterPanel.classList.toggle('hidden');
            const icon = filterToggle.querySelector('svg');
            icon.classList.toggle('rotate-180');
        });
    }
});
</script>
@endpush