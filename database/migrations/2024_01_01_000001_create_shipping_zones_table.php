<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // 'java' or 'luar_java'
            $table->decimal('cost', 10, 2);
            $table->json('cities'); // Array kota-kota
            $table->integer('distance_from_jakarta')->nullable(); // Jarak dari Jakarta dalam km
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_zones');
    }
};