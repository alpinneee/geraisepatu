<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Customer\WishlistController;
use App\Http\Controllers\ProfileController as LaravelProfileController;
use App\Http\Controllers\ShippingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Customer Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');


// Product Routes
Route::get('/products', [CustomerProductController::class, 'index'])->name('products.index');
Route::get('/products/search', [CustomerProductController::class, 'search'])->name('products.search');
Route::get('/products/category/{slug}', [CustomerProductController::class, 'category'])->name('products.category');
Route::get('/products/{slug}', [CustomerProductController::class, 'show'])->name('products.show');

// Category Routes
Route::get('/categories', [CustomerProductController::class, 'categories'])->name('categories.index');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::post('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

// Shipping calculation (no auth required)
Route::post('/shipping/calculate', [CheckoutController::class, 'calculateShipping'])->name('shipping.calculate');
Route::post('/api/shipping/calculate', [ShippingController::class, 'calculateShipping'])->name('api.shipping.calculate');
Route::get('/api/shipping/cities', [ShippingController::class, 'getCities'])->name('api.shipping.cities');

// Checkout Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/payment/{order}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/order/{order}/continue-payment', [CheckoutController::class, 'continuePayment'])->name('order.continue-payment');
});

// Midtrans Routes (no auth required for webhooks)
Route::post('/midtrans/notification', [\App\Http\Controllers\MidtransController::class, 'notification'])
    ->middleware('midtrans.webhook')
    ->name('midtrans.notification');
Route::get('/midtrans/finish', [\App\Http\Controllers\MidtransController::class, 'finish'])->name('midtrans.finish');
Route::get('/midtrans/unfinish', [\App\Http\Controllers\MidtransController::class, 'unfinish'])->name('midtrans.unfinish');
Route::get('/midtrans/error', [\App\Http\Controllers\MidtransController::class, 'error'])->name('midtrans.error');

// Webhook API Routes
Route::post('/api/webhook/midtrans', [\App\Http\Controllers\Admin\BillingController::class, 'webhook'])->name('api.webhook.midtrans');

// Customer Profile Routes
Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [CustomerProfileController::class, 'index'])->name('index');
    Route::put('/', [CustomerProfileController::class, 'update'])->name('update');
    Route::get('/edit', [CustomerProfileController::class, 'index'])->name('edit');
    Route::get('/change-password', [CustomerProfileController::class, 'showChangePasswordForm'])->name('change-password');
    Route::put('/change-password', [CustomerProfileController::class, 'changePassword'])->name('update-password');
    Route::get('/orders', [CustomerProfileController::class, 'orders'])->name('orders');
    Route::get('/orders/id', function() {
        return redirect()->route('profile.orders')->with('error', 'Please select a specific order from the list.');
    });
    Route::get('/orders/{order}', [CustomerProfileController::class, 'showOrder'])->name('orders.show')->where('order', '[0-9]+');
    Route::get('/addresses', [CustomerProfileController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [CustomerProfileController::class, 'storeAddress'])->name('addresses.store');
    Route::put('/addresses/{address}', [CustomerProfileController::class, 'updateAddress'])->name('addresses.update');
    Route::delete('/addresses/{address}', [CustomerProfileController::class, 'deleteAddress'])->name('addresses.delete');
});

// Order Review Route
Route::middleware(['auth'])->post('/orders/{order}/review', [CustomerProfileController::class, 'storeReview'])->name('orders.review');

// Orders Index Route (for compatibility with menu/profile links)
Route::middleware(['auth'])->get('/orders', function () {
    return redirect()->route('profile.orders');
})->name('orders.index');

// Wishlist Routes
Route::middleware(['auth'])->prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', [WishlistController::class, 'index'])->name('index');
    Route::post('/add', [WishlistController::class, 'add'])->name('add');
    Route::post('/remove', [WishlistController::class, 'remove'])->name('remove');
    Route::post('/clear', [WishlistController::class, 'clear'])->name('clear');
    Route::post('/move-to-cart', [WishlistController::class, 'moveToCart'])->name('move-to-cart');
    Route::get('/check', [WishlistController::class, 'check'])->name('check');
});



// Review Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/products/{product}/review', [CustomerProductController::class, 'storeReview'])->name('products.review');
});

// Dashboard Route (for auth redirect compatibility)
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->name('dashboard');

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Admin Product Routes
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::delete('product-images/{id}', [\App\Http\Controllers\Admin\ProductImageController::class, 'destroy'])->name('product-images.destroy');
    
    // Admin Category Routes
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    
    // Admin Banner Routes
    Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class);
    
    // Admin Product Size Routes
    Route::get('/product-sizes', [\App\Http\Controllers\Admin\ProductSizeController::class, 'index'])->name('product-sizes.index');
    Route::get('/products/{product}/sizes', [\App\Http\Controllers\Admin\ProductSizeController::class, 'show'])->name('product-sizes.show');
    Route::post('/products/{product}/sizes', [\App\Http\Controllers\Admin\ProductSizeController::class, 'store'])->name('product-sizes.store');
    Route::delete('/products/{product}/sizes/{size}', [\App\Http\Controllers\Admin\ProductSizeController::class, 'destroy'])->name('product-sizes.destroy');
    
    // Admin Order Routes
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->except(['create', 'store', 'destroy']);
    Route::get('/orders/{order}/invoice', [\App\Http\Controllers\Admin\OrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('/orders/export', [\App\Http\Controllers\Admin\OrderController::class, 'export'])->name('orders.export');
    Route::patch('/orders/{order}/confirm-payment', [\App\Http\Controllers\Admin\OrderController::class, 'confirmPayment'])->name('orders.confirm-payment');
    Route::patch('/orders/{order}/update-status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    
    // Admin User Routes
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['create', 'store', 'show']);
    
    // Admin Billing Routes
    Route::get('/billing', [\App\Http\Controllers\Admin\BillingController::class, 'index'])->name('billing.index');
    Route::post('/billing/sync', [\App\Http\Controllers\Admin\BillingController::class, 'syncStatus'])->name('billing.sync');
    Route::get('/billing/invoice/{order}', [\App\Http\Controllers\Admin\BillingController::class, 'downloadInvoice'])->name('billing.invoice');
    
    // Admin Security Routes
    Route::get('/security', [\App\Http\Controllers\Admin\SecurityController::class, 'index'])->name('security.index');
    Route::put('/security/password', [\App\Http\Controllers\Admin\SecurityController::class, 'updatePassword'])->name('security.password');
    Route::post('/security/password-strength', [\App\Http\Controllers\Admin\SecurityController::class, 'getPasswordStrength'])->name('security.password-strength');
    Route::post('/security/logout-all', [\App\Http\Controllers\Admin\SecurityController::class, 'logoutAllDevices'])->name('security.logout-all');
    
    // Admin Contact Routes
    Route::resource('contacts', \App\Http\Controllers\Admin\ContactController::class)->only(['index', 'show', 'destroy']);
    Route::patch('/contacts/{contact}/mark-as-replied', [\App\Http\Controllers\Admin\ContactController::class, 'markAsReplied'])->name('contacts.mark-as-replied');
    Route::post('/contacts/mark-as-read', [\App\Http\Controllers\Admin\ContactController::class, 'markAsRead'])->name('contacts.mark-as-read');
    Route::delete('/contacts/bulk-delete', [\App\Http\Controllers\Admin\ContactController::class, 'bulkDelete'])->name('contacts.bulk-delete');
    

});

// Google OAuth Routes
require __DIR__.'/google.php';
require __DIR__.'/google-fix.php';
require __DIR__.'/google-test.php';
require __DIR__.'/google-debug.php';

// Test Routes
require __DIR__.'/test.php';
require __DIR__.'/debug.php';
require __DIR__.'/checkout-debug.php';
require __DIR__.'/checkout-test.php';
require __DIR__.'/cart-test.php';
require __DIR__.'/order-debug.php';
require __DIR__.'/test-email.php';

// Laravel Breeze Routes
require __DIR__.'/auth.php';
