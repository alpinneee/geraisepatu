<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

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