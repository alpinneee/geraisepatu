<?php

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

// Test route untuk simulasi merge cart
Route::get('/test-cart-merge', function () {
    // Simulasi guest cart
    $guestCart = [
        [
            'product_id' => 1,
            'size' => 42,
            'quantity' => 2
        ],
        [
            'product_id' => 2,
            'size' => 40,
            'quantity' => 1
        ]
    ];
    
    Session::put('cart', $guestCart);
    
    return response()->json([
        'message' => 'Guest cart created',
        'guest_cart' => $guestCart,
        'session_cart' => Session::get('cart')
    ]);
});

Route::get('/test-login-merge', function () {
    // Simulasi login user
    $user = User::first();
    
    if (!$user) {
        return response()->json(['error' => 'No user found']);
    }
    
    // Login user
    Auth::login($user);
    
    // Cek cart setelah login
    $userCart = Cart::where('user_id', $user->id)->get();
    
    return response()->json([
        'message' => 'User logged in and cart merged',
        'user_id' => $user->id,
        'user_cart' => $userCart,
        'session_cart' => Session::get('cart', 'empty')
    ]);
});

Route::get('/test-cart-status', function () {
    $sessionCart = Session::get('cart', []);
    $userCart = [];
    
    if (Auth::check()) {
        $userCart = Cart::where('user_id', Auth::id())->with('product')->get();
    }
    
    return response()->json([
        'authenticated' => Auth::check(),
        'user_id' => Auth::id(),
        'session_cart' => $sessionCart,
        'user_cart' => $userCart,
        'cart_count_session' => collect($sessionCart)->sum('quantity'),
        'cart_count_db' => Auth::check() ? Cart::where('user_id', Auth::id())->sum('quantity') : 0
    ]);
});