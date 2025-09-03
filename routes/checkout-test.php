<?php

use Illuminate\Support\Facades\Route;

// Test checkout form
Route::get('/checkout-test', function () {
    // Ensure user is logged in for testing
    if (!\Illuminate\Support\Facades\Auth::check()) {
        // Create a test user or login
        $user = \App\Models\User::first();
        if ($user) {
            \Illuminate\Support\Facades\Auth::login($user);
        }
    }
    
    // Add a test item to cart if cart is empty
    if (\Illuminate\Support\Facades\Auth::check()) {
        $cartCount = \App\Models\Cart::where('user_id', \Illuminate\Support\Facades\Auth::id())->count();
        if ($cartCount === 0) {
            $product = \App\Models\Product::first();
            if ($product) {
                \App\Models\Cart::create([
                    'user_id' => \Illuminate\Support\Facades\Auth::id(),
                    'product_id' => $product->id,
                    'quantity' => 1
                ]);
            }
        }
    }
    
    return view('test.checkout-test');
});