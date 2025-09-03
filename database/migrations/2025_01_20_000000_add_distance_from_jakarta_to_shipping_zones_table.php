<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_zones', function (Blueprint $table) {
            if (!Schema::hasColumn('shipping_zones', 'distance_from_jakarta')) {
                $table->integer('distance_from_jakarta')->nullable()->after('cities');
            }
        });
    }

    public function down(): void
    {
        Schema::table('shipping_zones', function (Blueprint $table) {
            if (Schema::hasColumn('shipping_zones', 'distance_from_jakarta')) {
                $table->dropColumn('distance_from_jakarta');
            }
        });
    }
};