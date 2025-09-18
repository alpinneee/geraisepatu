@extends('layouts.app')

@section('title', 'Wishlist')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Wishlist ({{ $wishlistItems->count() }})</h1>
            @if($wishlistItems->count() > 0)
                <button onclick="clearWishlist()" class="text-red-600 hover:text-red-700 text-sm font-medium">
                    Clear All
                </button>
            @endif
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($wishlistItems->count() > 0)
            <div class="space-y-3">
                @foreach($wishlistItems as $item)
                    <div class="bg-white border rounded-lg p-4 hover:shadow-sm transition-shadow">
                        <div class="flex gap-4">
                            <!-- Product Image -->
                            <div class="relative flex-shrink-0">
                                @if($item->product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-20 h-20 object-cover rounded">
                                @else
                                    <div class="w-20 h-20 bg-gray-100 rounded flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-medium text-gray-900 truncate">
                                            <a href="{{ route('products.show', $item->product->slug) }}" class="hover:text-blue-600">
                                                {{ $item->product->name }}
                                            </a>
                                        </h3>
                                        <p class="text-lg font-semibold text-gray-900 mt-1">
                                            Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                        </p>
                                        <div class="flex items-center gap-2 mt-2">
                                            @if($item->product->stock > 0)
                                                <span class="text-green-600 text-sm">In Stock</span>
                                            @else
                                                <span class="text-red-600 text-sm">Out of Stock</span>
                                            @endif
                                            @if($item->product->category)
                                                <span class="text-gray-400">•</span>
                                                <span class="text-gray-500 text-sm">{{ $item->product->category->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="flex items-center gap-2 ml-4">
                                        @if($item->product->stock > 0)
                                            <button onclick="addToCart({{ $item->product->id }})" 
                                                    class="bg-blue-600 text-white px-3 py-1.5 rounded text-sm hover:bg-blue-700">
                                                Add to Cart
                                            </button>
                                        @endif
                                        <button onclick="removeFromWishlist({{ $item->product->id }})" 
                                                class="text-red-600 hover:text-red-700 p-1.5">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($wishlistItems->hasPages())
                <div class="mt-6">
                    {{ $wishlistItems->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Your wishlist is empty</h3>
                <p class="text-gray-500 mb-4">Start adding products to save them for later.</p>
                <a href="{{ route('products.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Browse Products
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Clear Wishlist Modal -->
<div id="clearWishlistModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-lg max-w-sm w-full">
        <div class="p-4">
            <h3 class="font-medium text-gray-900 mb-2">Clear Wishlist</h3>
            <p class="text-gray-600 text-sm mb-4">Remove all items from your wishlist?</p>
            <div class="flex gap-2">
                <button onclick="closeClearWishlistModal()" class="flex-1 px-3 py-2 border border-gray-300 text-gray-700 rounded text-sm hover:bg-gray-50">
                    Cancel
                </button>
                <button id="confirmClearWishlist" class="flex-1 px-3 py-2 bg-red-600 text-white rounded text-sm hover:bg-red-700">
                    Clear All
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Remove Item Modal -->
<div id="removeItemModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-lg max-w-sm w-full">
        <div class="p-4">
            <h3 class="font-medium text-gray-900 mb-2">Remove from Wishlist</h3>
            <p class="text-gray-600 text-sm mb-4">Remove this item from your wishlist?</p>
            <div class="flex gap-2">
                <button onclick="closeRemoveItemModal()" class="flex-1 px-3 py-2 border border-gray-300 text-gray-700 rounded text-sm hover:bg-gray-50">
                    Cancel
                </button>
                <button id="confirmRemoveItem" class="flex-1 px-3 py-2 bg-red-600 text-white rounded text-sm hover:bg-red-700">
                    Remove
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentProductId = null;
let selectedSize = null;

// Remove from wishlist
function removeFromWishlist(productId) {
    currentProductId = productId;
    document.getElementById('removeItemModal').classList.remove('hidden');
}

function closeRemoveItemModal() {
    document.getElementById('removeItemModal').classList.add('hidden');
    currentProductId = null;
}

document.getElementById('confirmRemoveItem').addEventListener('click', function() {
    if (currentProductId) {
        fetch('/wishlist/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: currentProductId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error removing product from wishlist');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error removing product from wishlist');
        });
    }
});

// Add to cart with size selection
function addToCart(productId) {
    currentProductId = productId;
    // For now, redirect to product page - you can enhance this with size selection
    window.location.href = `/products/${productId}?add_to_cart=1&quantity=1`;
}



// Clear wishlist
function clearWishlist() {
    document.getElementById('clearWishlistModal').classList.remove('hidden');
}

function closeClearWishlistModal() {
    document.getElementById('clearWishlistModal').classList.add('hidden');
}

document.getElementById('confirmClearWishlist').addEventListener('click', function() {
    fetch('/wishlist/clear', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.ok) {
            location.reload();
        } else {
            alert('Error clearing wishlist');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error clearing wishlist');
    });
});

// Close modals when clicking outside
window.onclick = function(event) {
    const clearModal = document.getElementById('clearWishlistModal');
    const removeModal = document.getElementById('removeItemModal');
    
    if (event.target === clearModal) {
        closeClearWishlistModal();
    }
    if (event.target === removeModal) {
        closeRemoveItemModal();
    }
}

// Close modals with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeClearWishlistModal();
        closeRemoveItemModal();
    }
});
</script>
@endsection 