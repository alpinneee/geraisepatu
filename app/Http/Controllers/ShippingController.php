<?php

namespace App\Http\Controllers;

use App\Models\ShippingZone;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function calculateShipping(Request $request)
    {
        $city = $request->input('city');
        $cost = ShippingZone::getShippingCost($city);
        
        // Simulasi berbagai ekspedisi dengan harga berbeda berdasarkan jarak
        $expeditions = [
            'jne_reg' => ['name' => 'JNE REG', 'multiplier' => 1.0, 'estimation' => '2-3 hari'],
            'jne_yes' => ['name' => 'JNE YES', 'multiplier' => 1.5, 'estimation' => '1-2 hari'],
            'jnt_reg' => ['name' => 'J&T REG', 'multiplier' => 0.9, 'estimation' => '2-4 hari'],
            'sicepat_reg' => ['name' => 'SiCepat REG', 'multiplier' => 0.8, 'estimation' => '2-3 hari']
        ];
        
        $shippingOptions = [];
        foreach ($expeditions as $code => $expedition) {
            $expeditionCost = (int)($cost * $expedition['multiplier']);
            $shippingOptions[$code] = [
                'cost' => $expeditionCost,
                'estimation' => $expedition['estimation'],
                'logistic' => explode(' ', $expedition['name'])[0],
                'service' => $expedition['name']
            ];
        }
        
        return response()->json($shippingOptions);
    }

    public function getCities()
    {
        $cities = ShippingZone::getAllCities();
        return response()->json($cities);
    }
}