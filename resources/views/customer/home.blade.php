@extends('layouts.customer')

@section('title', 'Beranda')



@section('content')
<div class="bg-white">
    <!-- Hero Banner Section -->
    @if($banners->isNotEmpty())
    <div class="relative overflow-hidden pt-10">
        <div id="carousel" class="flex transition-transform duration-500 ease-in-out">
            @foreach($banners as $banner)
            <div class="w-full flex-shrink-0 relative h-54 sm:h-62 md:h-70 lg:h-86">
                <img class="w-full h-full object-cover" 
                     src="{{ asset('storage/' . $banner->image) }}" 
                     alt="{{ $banner->title }}">
                <div class="absolute inset-0 bg-black/40"></div>
                <div class="absolute inset-0 flex items-center">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 w-full">
                        <div class="max-w-2xl">
                            <h2 class="text-2xl md:text-4xl lg:text-5xl font-bold text-white mb-4">
                                {{ $banner->title }}
                            </h2>
                            <p class="text-lg md:text-xl text-white/90 mb-6">
                                {{ substr($banner->description, 0, 60) }}{{ strlen($banner->description) > 60 ? '...' : '' }}
                            </p>
                            @if($banner->button_text && $banner->button_url)
                            <a href="{{ $banner->button_url }}" class="inline-block px-6 py-3 bg-white text-gray-900 text-lg font-semibold rounded-lg hover:bg-gray-100 transition">
                                {{ $banner->button_text }}
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        @if($banners->count() > 1)
        <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 flex space-x-1">
            @foreach($banners as $index => $banner)
            <div class="carousel-dot w-2 h-2 rounded-full bg-white/50 hover:bg-white transition" data-slide="{{ $index }}"></div>
            @endforeach
        </div>
        @endif
    </div>
    @endif

    <!-- Featured Products Section -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 sm:py-10">
        <div class="flex items-center justify-between">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Produk Unggulan</h2>
            <a href="{{ route('products.index') }}" class="text-xs font-semibold text-gray-700 hover:underline">
                Lihat Semua <span aria-hidden="true">→</span>
            </a>
        </div>

        <div class="mt-4 grid grid-cols-2 gap-x-3 gap-y-4 sm:gap-x-4 sm:gap-y-6 sm:grid-cols-3 lg:grid-cols-4 xl:gap-x-6">
            @forelse($featuredProducts as $product)
                <div class="group relative border border-gray-200 rounded-lg p-2 sm:p-3 bg-white hover:shadow-md transition">
                    <div class="aspect-square w-full overflow-hidden rounded bg-gray-100 group-hover:opacity-80 flex items-center justify-center">
                        @if($product->images->isNotEmpty())
                            @php
                                $primaryImage = $product->images->where('is_primary', true)->first();
                                $imagePath = $primaryImage ? $primaryImage->image_path : $product->images->first()->image_path;
                            @endphp
                            <img src="{{ asset('storage/' . $imagePath) }}" 
                                alt="{{ $product->name }}" 
                                class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                        @else
                            <div class="flex h-full items-center justify-center bg-gray-100">
                                <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
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
            @empty
                <div class="col-span-full py-8 text-center">
                    <p class="text-gray-400 text-sm">Tidak ada produk unggulan saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Categories Section -->
    <div class="bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Kategori</h2>
                <a href="{{ route('categories.index') }}" class="text-xs font-semibold text-gray-700 hover:underline">
                    Lihat Semua <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-y-6 gap-x-4 sm:grid-cols-3 lg:grid-cols-4">
                @forelse($categories as $category)
                    <a href="{{ route('products.category', $category->slug) }}" class="group border border-gray-200 rounded-md p-2 bg-white hover:shadow-sm transition flex flex-col items-center">
                        <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded bg-gray-100 flex items-center justify-center">
                            @if($category->image)
                                <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="h-20 w-20 object-cover object-center group-hover:opacity-80 rounded bg-white">
                            @else
                                <div class="flex h-20 w-20 items-center justify-center bg-gray-100 group-hover:bg-gray-200 rounded">
                                    <span class="text-xs font-medium text-gray-600 text-center px-1">{{ substr($category->name, 0, 8) }}</span>
                                </div>
                            @endif
                        </div>
                        <h3 class="mt-2 text-xs font-semibold text-gray-900 text-center">{{ $category->name }}</h3>
                        <p class="mt-0.5 text-[11px] text-gray-500">{{ $category->products_count ?? 0 }} produk</p>
                    </a>
                @empty
                    <div class="col-span-full py-8 text-center">
                        <p class="text-gray-400 text-sm">Tidak ada kategori saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sale Products Section -->
    @if($saleProducts->isNotEmpty())
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Produk Diskon</h2>
                <a href="{{ route('products.index') }}?sale=1" class="text-xs font-semibold text-gray-700 hover:underline">
                    Lihat Semua <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-x-4 gap-y-6 sm:grid-cols-3 lg:grid-cols-4 xl:gap-x-6">
                @foreach($saleProducts as $product)
                    <div class="group relative border border-gray-200 rounded-md p-2 bg-white hover:shadow-sm transition">
                        <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded bg-gray-100 lg:aspect-none group-hover:opacity-80 lg:h-48 flex items-center justify-center">
                            @if($product->images->isNotEmpty())
                                @php
                                    $primaryImage = $product->images->where('is_primary', true)->first();
                                    $imagePath = $primaryImage ? $primaryImage->image_path : $product->images->first()->image_path;
                                @endphp
                                <img src="{{ asset('storage/' . $imagePath) }}" 
                                    alt="{{ $product->name }}" 
                                    class="h-full w-full object-cover object-center lg:h-full lg:w-full">
                            @else
                                <div class="flex h-full items-center justify-center bg-gray-100">
                                    <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-1 right-1 bg-gray-900 text-white px-2 py-0.5 text-[10px] font-bold rounded">
                                -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                            </div>
                        </div>
                        <div class="mt-2 flex justify-between items-center">
                            <div>
                                <h3 class="text-xs font-medium text-gray-800 truncate">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                <p class="mt-0.5 text-[11px] text-gray-500">{{ $product->category->name }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-gray-900">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</span>
                                <div class="text-[10px] text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('carousel');
    const dots = document.querySelectorAll('.carousel-dot');
    let currentSlide = 0;
    const totalSlides = dots.length;
    
    function updateCarousel() {
        carousel.style.transform = `translateX(-${currentSlide * 100}%)`;
        dots.forEach((dot, index) => {
            if (index === currentSlide) {
                dot.classList.remove('bg-opacity-50');
                dot.classList.add('bg-opacity-100');
            } else {
                dot.classList.remove('bg-opacity-100');
                dot.classList.add('bg-opacity-50');
            }
        });
    }
    
    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        updateCarousel();
    }
    
    // Auto slide every 5 seconds (only if more than 1 slide)
    if (totalSlides > 1) {
        setInterval(nextSlide, 5000);
    }
    
    // Manual navigation
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentSlide = index;
            updateCarousel();
        });
    });
    
    // Initialize
    updateCarousel();
});
</script>
@endpush