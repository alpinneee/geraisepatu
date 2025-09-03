<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

// Debug route to check order access
Route::middleware(['auth'])->get('/debug/orders/{order}', function (Order $order) {
    $user = Auth::user();
    
    return response()->json([
        'order_id' => $order->id,
        'order_user_id' => $order->user_id,
        'current_user_id' => $user->id,
        'belongs_to_user' => $order->user_id === $user->id,
        'order_details' => $order->toArray()
    ]);
});

// Debug route to list user orders
Route::middleware(['auth'])->get('/debug/my-orders', function () {
    $user = Auth::user();
    $orders = Order::where('user_id', $user->id)->get();
    
    return response()->json([
        'user_id' => $user->id,
        'orders_count' => $orders->count(),
        'orders' => $orders->toArray()
    ]);
});

// Debug route to create sample order
Route::middleware(['auth'])->get('/debug/create-sample-order', function () {
    $user = Auth::user();
    $product = \App\Models\Product::first();
    
    if (!$product) {
        return response()->json(['error' => 'No products found']);
    }
    
    $order = \App\Models\Order::create([
        'user_id' => $user->id,
        'order_number' => 'ORD-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
        'status' => 'delivered',
        'total_amount' => 150000,
        'shipping_cost' => 15000,
        'payment_method' => 'qris',
        'payment_status' => 'paid',
        'shipping_address' => json_encode([
            'name' => $user->name,
            'phone' => '081234567890',
            'address' => 'Jl. Sample No. 123',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
            'postal_code' => '12345'
        ])
    ]);
    
    \App\Models\OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 1,
        'price' => 135000,
        'size' => '42'
    ]);
    
    return response()->json([
        'message' => 'Sample order created',
        'order_id' => $order->id,
        'order_number' => $order->order_number,
        'view_url' => route('profile.orders.show', $order)
    ]);
});