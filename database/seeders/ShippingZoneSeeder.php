<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingZoneSeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            [
                'name' => 'Jabodetabek',
                'type' => 'jabodetabek',
                'cost' => 10000,
                'distance_from_jakarta' => 25,
                'cities' => json_encode([
                    'Jakarta', 'Depok', 'Bogor', 'Tangerang', 'Bekasi'
                ])
            ],
            [
                'name' => 'Jawa Barat',
                'type' => 'jawa_barat',
                'cost' => 15000,
                'distance_from_jakarta' => 150,
                'cities' => json_encode([
                    'Bandung', 'Cimahi', 'Sukabumi', 'Garut', 'Tasikmalaya',
                    'Cianjur', 'Purwakarta', 'Subang', 'Karawang'
                ])
            ],
            [
                'name' => 'Jawa Tengah',
                'type' => 'jawa_tengah',
                'cost' => 20000,
                'distance_from_jakarta' => 450,
                'cities' => json_encode([
                    'Semarang', 'Surakarta', 'Yogyakarta', 'Magelang', 'Salatiga',
                    'Purwokerto', 'Tegal', 'Pekalongan', 'Klaten', 'Boyolali'
                ])
            ],
            [
                'name' => 'Jawa Timur',
                'type' => 'jawa_timur',
                'cost' => 25000,
                'distance_from_jakarta' => 800,
                'cities' => json_encode([
                    'Surabaya', 'Malang', 'Kediri', 'Blitar', 'Madiun',
                    'Jember', 'Probolinggo', 'Pasuruan', 'Mojokerto'
                ])
            ],
            [
                'name' => 'Banten',
                'type' => 'banten',
                'cost' => 12000,
                'distance_from_jakarta' => 100,
                'cities' => json_encode([
                    'Serang', 'Cilegon', 'Pandeglang', 'Lebak'
                ])
            ],
            [
                'name' => 'Luar Pulau Jawa',
                'type' => 'luar_java',
                'cost' => 30000,
                'distance_from_jakarta' => 1500,
                'cities' => json_encode([
                    'Medan', 'Palembang', 'Pekanbaru', 'Padang', 'Jambi',
                    'Bandar Lampung', 'Bengkulu', 'Batam', 'Tanjung Pinang',
                    'Pontianak', 'Banjarmasin', 'Palangkaraya', 'Samarinda',
                    'Balikpapan', 'Tarakan', 'Manado', 'Makassar', 'Palu',
                    'Kendari', 'Gorontalo', 'Mamuju', 'Denpasar', 'Mataram',
                    'Kupang', 'Ambon', 'Ternate', 'Jayapura', 'Sorong'
                ])
            ]
        ];

        foreach ($zones as $zone) {
            DB::table('shipping_zones')->insert(array_merge($zone, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }
}