<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_instructions',
                'shipping_estimation'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('payment_instructions')->nullable()->after('payment_details');
            $table->string('shipping_estimation')->nullable()->after('shipping_expedition_name');
        });
    }
};