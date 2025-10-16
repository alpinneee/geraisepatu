<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::take(5)->get();
        $users = User::where('email', '!=', 'admin@example.com')->take(3)->get();
        
        $comments = [
            'Produk sangat bagus, kualitas sesuai harga. Pengiriman cepat!',
            'Sepatu nyaman dipakai, bahan berkualitas. Recommended!',
            'Pelayanan memuaskan, produk original. Terima kasih!',
            'Kualitas oke, sesuai ekspektasi. Akan order lagi.',
            'Sepatu keren, cocok untuk olahraga. Mantap!',
        ];
        
        foreach ($products as $product) {
            foreach ($users as $user) {
                if (rand(1, 3) === 1) { // 33% chance to create review
                    Review::create([
                        'product_id' => $product->id,
                        'user_id' => $user->id,
                        'rating' => rand(4, 5),
                        'comment' => $comments[array_rand($comments)],
                        'is_approved' => rand(0, 1) === 1,
                    ]);
                }
            }
        }
    }
}