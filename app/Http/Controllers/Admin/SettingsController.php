<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string',
            'address' => 'nullable|string',
            'timezone' => 'nullable|string',
            'currency' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'whatsapp_number' => 'nullable|string',
            'default_shipping_cost' => 'nullable|numeric|min:0',
            'default_product_weight' => 'nullable|numeric|min:0',
            'sender_address' => 'nullable|string',
            'origin_city' => 'nullable|string',
            'origin_postal_code' => 'nullable|string',
            'maintenance_message' => 'nullable|string',
        ]);

        // Store general settings
        cache()->put('settings.site_name', $request->site_name);
        cache()->put('settings.site_description', $request->site_description);
        cache()->put('settings.contact_email', $request->contact_email);
        cache()->put('settings.contact_phone', $request->contact_phone);
        cache()->put('settings.address', $request->address);
        cache()->put('settings.timezone', $request->timezone ?? 'Asia/Jakarta');
        cache()->put('settings.currency', $request->currency ?? 'IDR');
        
        // Store social media settings
        cache()->put('settings.facebook_url', $request->facebook_url);
        cache()->put('settings.instagram_url', $request->instagram_url);
        cache()->put('settings.twitter_url', $request->twitter_url);
        cache()->put('settings.whatsapp_number', $request->whatsapp_number);
        
        // Store shipping settings
        cache()->put('settings.shipping_jne', $request->has('shipping_jne'));
        cache()->put('settings.shipping_jnt', $request->has('shipping_jnt'));
        cache()->put('settings.shipping_pos', $request->has('shipping_pos'));
        cache()->put('settings.shipping_sicepat', $request->has('shipping_sicepat'));
        cache()->put('settings.shipping_tiki', $request->has('shipping_tiki'));
        cache()->put('settings.shipping_anteraja', $request->has('shipping_anteraja'));
        cache()->put('settings.default_shipping_cost', $request->default_shipping_cost ?? 15000);
        cache()->put('settings.default_product_weight', $request->default_product_weight ?? 500);
        cache()->put('settings.sender_address', $request->sender_address);
        cache()->put('settings.origin_city', $request->origin_city ?? 'Jakarta');
        cache()->put('settings.origin_postal_code', $request->origin_postal_code ?? '10110');
        
        // Store maintenance settings
        cache()->put('settings.maintenance_mode', $request->has('maintenance_mode'));
        cache()->put('settings.maintenance_message', $request->maintenance_message ?? 'Website sedang dalam perbaikan. Silakan kembali lagi nanti.');

        return redirect()->route('admin.settings')->with('success', 'Pengaturan berhasil disimpan');
    }
}