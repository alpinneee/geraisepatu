@props(['productId', 'size' => 'md'])

@php
$sizeClasses = [
    'sm' => 'w-8 h-8 p-1.5',
    'md' => 'w-10 h-10 p-2',
    'lg' => 'w-12 h-12 p-2.5'
];
$iconSizes = [
    'sm' => 'w-4 h-4',
    'md' => 'w-5 h-5', 
    'lg' => 'w-6 h-6'
];
@endphp

@auth
<button 
    onclick="toggleWishlist({{ $productId }})" 
    id="wishlist-btn-{{ $productId }}"
    class="wishlist-btn {{ $sizeClasses[$size] }} bg-white border border-gray-300 rounded-full hover:bg-gray-50 transition-all duration-200 flex items-center justify-center group"
    data-product-id="{{ $productId }}"
    title="Add to Wishlist">
    <svg class="{{ $iconSizes[$size] }} text-gray-400 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
    </svg>
</button>
@else
<button 
    onclick="alert('Please login to add items to wishlist')" 
    class="{{ $sizeClasses[$size] }} bg-white border border-gray-300 rounded-full hover:bg-gray-50 transition-all duration-200 flex items-center justify-center"
    title="Login to Add to Wishlist">
    <svg class="{{ $iconSizes[$size] }} text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
    </svg>
</button>
@endauth

@auth
@push('scripts')
<script>
function toggleWishlist(productId) {
    const btn = document.getElementById(`wishlist-btn-${productId}`);
    const icon = btn.querySelector('svg');
    
    fetch('/wishlist/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update button to show added state
            icon.classList.remove('text-gray-400');
            icon.classList.add('text-red-500');
            icon.setAttribute('fill', 'currentColor');
            btn.title = 'Added to Wishlist';
            
            // Show success message
            showToast('Product added to wishlist!', 'success');
        } else {
            if (data.message.includes('already')) {
                // Already in wishlist, remove it
                removeFromWishlist(productId);
            } else {
                showToast(data.message, 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error adding to wishlist', 'error');
    });
}

function removeFromWishlist(productId) {
    fetch('/wishlist/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const btn = document.getElementById(`wishlist-btn-${productId}`);
            const icon = btn.querySelector('svg');
            
            // Update button to show removed state
            icon.classList.remove('text-red-500');
            icon.classList.add('text-gray-400');
            icon.setAttribute('fill', 'none');
            btn.title = 'Add to Wishlist';
            
            showToast('Product removed from wishlist!', 'success');
        }
    });
}

function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-4 py-2 rounded-lg text-white z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Check wishlist status on page load
document.addEventListener('DOMContentLoaded', function() {
    const wishlistBtns = document.querySelectorAll('.wishlist-btn');
    wishlistBtns.forEach(btn => {
        const productId = btn.dataset.productId;
        if (productId) {
            checkWishlistStatus(productId);
        }
    });
});

function checkWishlistStatus(productId) {
    fetch(`/wishlist/check?product_id=${productId}`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        const btn = document.getElementById(`wishlist-btn-${productId}`);
        const icon = btn.querySelector('svg');
        
        if (data.in_wishlist) {
            icon.classList.remove('text-gray-400');
            icon.classList.add('text-red-500');
            icon.setAttribute('fill', 'currentColor');
            btn.title = 'Remove from Wishlist';
        }
    });
}
</script>
@endpush
@endauth