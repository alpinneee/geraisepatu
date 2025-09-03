<?php

namespace App\Listeners;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Session;

class MergeGuestCart
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        $guestCart = Session::get('cart', []);
        
        if (empty($guestCart)) {
            return;
        }
        
        foreach ($guestCart as $guestItem) {
            // Validasi produk masih tersedia
            $product = Product::find($guestItem['product_id']);
            if (!$product || !$product->is_active) {
                continue;
            }
            
            // Cek apakah item sudah ada di cart user
            $existingCartItem = Cart::where('user_id', $user->id)
                ->where('product_id', $guestItem['product_id'])
                ->where('size', $guestItem['size'])
                ->first();
            
            if ($existingCartItem) {
                // Jika sudah ada, tambahkan quantity (dengan validasi stock)
                $newQuantity = $existingCartItem->quantity + $guestItem['quantity'];
                if ($newQuantity <= $product->stock) {
                    $existingCartItem->update(['quantity' => $newQuantity]);
                }
            } else {
                // Jika belum ada, buat item baru (dengan validasi stock)
                if ($guestItem['quantity'] <= $product->stock) {
                    Cart::create([
                        'user_id' => $user->id,
                        'product_id' => $guestItem['product_id'],
                        'size' => $guestItem['size'],
                        'quantity' => $guestItem['quantity'],
                    ]);
                }
            }
        }
        
        // Hapus cart dari session setelah merge
        Session::forget('cart');
    }
}