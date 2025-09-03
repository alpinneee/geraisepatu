<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingZone;
use Illuminate\Http\Request;

class ShippingZoneController extends Controller
{
    public function index()
    {
        $zones = ShippingZone::all();
        return view('admin.shipping-zones.index', compact('zones'));
    }

    public function edit(ShippingZone $shippingZone)
    {
        return view('admin.shipping-zones.edit', compact('shippingZone'));
    }

    public function update(Request $request, ShippingZone $shippingZone)
    {
        $request->validate([
            'cost' => 'required|numeric|min:0'
        ]);

        $shippingZone->update([
            'cost' => $request->cost
        ]);

        return redirect()->route('admin.shipping-zones.index')
            ->with('success', 'Ongkos kirim berhasil diperbarui');
    }
}