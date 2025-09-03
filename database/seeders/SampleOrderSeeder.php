<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class SampleOrderSeeder extends Seeder
{
    public function run()
    {
        // Get first customer user
        $customer = User::whereHas('roles', function($q) {
            $q->where('name', 'customer');
        })->first();

        if (!$customer) {
            $customer = User::where('email', 'customer1@example.com')->first();
        }

        if (!$customer) {
            echo "No customer user found. Please create a customer user first.\n";
            return;
        }

        // Get first product
        $product = Product::first();
        if (!$product) {
            echo "No products found. Please seed products first.\n";
            return;
        }

        // Create sample order
        $order = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'ORD-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
            'status' => 'delivered',
            'total_amount' => 150000,
            'shipping_cost' => 15000,
            'payment_method' => 'qris',
            'payment_status' => 'paid',
            'shipping_address' => json_encode([
                'name' => $customer->name,
                'phone' => '081234567890',
                'address' => 'Jl. Sample No. 123',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'postal_code' => '12345'
            ])
        ]);

        // Create order item
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 135000,
            'size' => '42'
        ]);

        echo "Sample order created with ID: {$order->id} for user: {$customer->email}\n";
    }
}