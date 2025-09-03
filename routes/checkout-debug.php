<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

// Debug route to test checkout validation
Route::get('/checkout-debug', function () {
    $user = Auth::user();
    
    $debug = [
        'user' => [
            'authenticated' => Auth::check(),
            'id' => $user?->id,
            'email' => $user?->email,
        ],
        'cart' => [],
        'session' => [
            'csrf_token' => csrf_token(),
            'session_id' => session()->getId(),
        ],
        'config' => [
            'app_url' => config('app.url'),
            'midtrans_client_key' => config('midtrans.client_key'),
        ]
    ];
    
    // Check cart items
    if (Auth::check()) {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        $debug['cart']['type'] = 'database';
        $debug['cart']['count'] = $cartItems->count();
        $debug['cart']['items'] = $cartItems->map(function($item) {
            return [
                'product_id' => $item->product_id,
                'product_name' => $item->product->name ?? 'Unknown',
                'quantity' => $item->quantity,
                'price' => $item->product->price ?? 0,
            ];
        });
    } else {
        $sessionCart = Session::get('cart', []);
        $debug['cart']['type'] = 'session';
        $debug['cart']['count'] = count($sessionCart);
        $debug['cart']['items'] = $sessionCart;
    }
    
    return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
});

// Test checkout validation
Route::post('/checkout-debug-test', function (Request $request) {
    try {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'shipping_expedition' => 'required|string',
            'shipping_cost' => 'nullable|numeric|min:0',
            'cod_fee' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Validation passed',
            'validated_data' => $validatedData
        ]);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors(),
            'request_data' => $request->all()
        ], 422);
    }
});