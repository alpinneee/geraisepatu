<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Just add the new unique constraint, keep the old one for now
        Schema::table('reviews', function (Blueprint $table) {
            $table->unique(['user_id', 'product_id', 'order_id'], 'reviews_user_product_order_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique('reviews_user_product_order_unique');
        });
    }
};