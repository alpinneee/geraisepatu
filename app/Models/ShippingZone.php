<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    protected $fillable = ['name', 'type', 'cost', 'cities', 'distance_from_jakarta'];

    protected $casts = [
        'cities' => 'array',
        'cost' => 'decimal:2',
        'distance_from_jakarta' => 'integer'
    ];

    public static function getShippingCost($city)
    {
        // Cari zona berdasarkan kota
        $zone = self::whereJsonContains('cities', $city)->first();
        
        if ($zone) {
            return $zone->cost;
        }
        
        // Jika kota tidak ditemukan, gunakan logika jarak dari Jakarta
        return self::calculateCostByDistance($city);
    }
    
    private static function calculateCostByDistance($city)
    {
        // Daftar kota dengan jarak dari Jakarta (dalam km)
        $cityDistances = [
            // Jabodetabek (0-50 km)
            'Jakarta' => 0, 'Depok' => 20, 'Bogor' => 60, 'Tangerang' => 25, 'Bekasi' => 30,
            
            // Jawa Barat (50-300 km)
            'Bandung' => 150, 'Cirebon' => 250, 'Sukabumi' => 120, 'Garut' => 200,
            'Tasikmalaya' => 300, 'Cianjur' => 100, 'Purwakarta' => 80,
            
            // Jawa Tengah (300-600 km)
            'Semarang' => 450, 'Yogyakarta' => 560, 'Surakarta' => 550, 'Magelang' => 520,
            'Purwokerto' => 350, 'Tegal' => 320, 'Pekalongan' => 380,
            
            // Jawa Timur (600-900 km)
            'Surabaya' => 800, 'Malang' => 850, 'Kediri' => 750, 'Blitar' => 780,
            'Madiun' => 650, 'Jember' => 950, 'Probolinggo' => 900,
            
            // Banten (50-150 km)
            'Serang' => 70, 'Cilegon' => 100, 'Pandeglang' => 120, 'Lebak' => 150,
            
            // Luar Jawa (1000+ km)
            'Medan' => 1400, 'Palembang' => 650, 'Pekanbaru' => 900, 'Padang' => 900,
            'Jambi' => 550, 'Bandar Lampung' => 250, 'Bengkulu' => 550, 'Batam' => 900,
            'Pontianak' => 700, 'Banjarmasin' => 650, 'Samarinda' => 1200, 'Balikpapan' => 1100,
            'Makassar' => 1300, 'Manado' => 1800, 'Denpasar' => 1000, 'Mataram' => 1200,
            'Kupang' => 1800, 'Ambon' => 2200, 'Jayapura' => 3400
        ];
        
        $distance = $cityDistances[ucfirst(strtolower($city))] ?? 1500; // Default untuk kota tidak dikenal
        
        // Hitung ongkos kirim berdasarkan jarak
        if ($distance <= 50) {
            return 10000; // Jabodetabek
        } elseif ($distance <= 300) {
            return 15000; // Jawa Barat
        } elseif ($distance <= 600) {
            return 20000; // Jawa Tengah
        } elseif ($distance <= 900) {
            return 25000; // Jawa Timur
        } else {
            return 30000; // Luar Jawa
        }
    }

    public static function getAllCities()
    {
        return self::all()->pluck('cities')->flatten()->sort()->values();
    }
}