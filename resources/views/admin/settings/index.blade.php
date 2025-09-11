@extends('layouts.admin')

@section('title', 'Pengaturan Umum')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <div>
            <h1 class="text-xl md:text-2xl font-bold text-gray-900">Pengaturan Umum</h1>
            <p class="text-sm md:text-base text-gray-600">Kelola pengaturan dasar website Anda</p>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Settings Tabs -->
    <div class="bg-white shadow rounded-lg">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-4 md:px-6" aria-label="Tabs">
                <button onclick="showTab('general')" id="tab-general" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm active">
                    Umum
                </button>
                <button onclick="showTab('social')" id="tab-social" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Media Sosial
                </button>
                <button onclick="showTab('shipping')" id="tab-shipping" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Pengiriman
                </button>
                <button onclick="showTab('maintenance')" id="tab-maintenance" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Maintenance
                </button>
            </nav>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- General Settings Tab -->
            <div id="content-general" class="tab-content p-4 md:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Pengaturan Umum</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Website</label>
                        <input type="text" name="site_name" value="{{ old('site_name', cache('settings.site_name', config('app.name'))) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @error('site_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Kontak</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', cache('settings.contact_email', 'admin@example.com')) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @error('contact_email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 md:mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Website</label>
                    <textarea name="site_description" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('site_description', cache('settings.site_description', 'Toko sepatu online terbaik')) }}</textarea>
                    @error('site_description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mt-4 md:mt-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', cache('settings.contact_phone', '')) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('contact_phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                        <input type="text" name="address" value="{{ old('address', cache('settings.address', '')) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mt-4 md:mt-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Zona Waktu</label>
                        <select name="timezone" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Asia/Jakarta" {{ old('timezone', cache('settings.timezone', 'Asia/Jakarta')) == 'Asia/Jakarta' ? 'selected' : '' }}>WIB (Jakarta)</option>
                            <option value="Asia/Makassar" {{ old('timezone', cache('settings.timezone', 'Asia/Jakarta')) == 'Asia/Makassar' ? 'selected' : '' }}>WITA (Makassar)</option>
                            <option value="Asia/Jayapura" {{ old('timezone', cache('settings.timezone', 'Asia/Jakarta')) == 'Asia/Jayapura' ? 'selected' : '' }}>WIT (Jayapura)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mata Uang</label>
                        <select name="currency" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="IDR" {{ old('currency', cache('settings.currency', 'IDR')) == 'IDR' ? 'selected' : '' }}>Rupiah (IDR)</option>
                            <option value="USD" {{ old('currency', cache('settings.currency', 'IDR')) == 'USD' ? 'selected' : '' }}>US Dollar (USD)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Social Media Tab -->
            <div id="content-social" class="tab-content p-4 md:p-6 hidden">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Media Sosial</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Facebook</label>
                        <input type="url" name="facebook_url" value="{{ old('facebook_url', cache('settings.facebook_url', '')) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="https://facebook.com/username">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Instagram</label>
                        <input type="url" name="instagram_url" value="{{ old('instagram_url', cache('settings.instagram_url', '')) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="https://instagram.com/username">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Twitter</label>
                        <input type="url" name="twitter_url" value="{{ old('twitter_url', cache('settings.twitter_url', '')) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="https://twitter.com/username">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp</label>
                        <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', cache('settings.whatsapp_number', '')) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="628123456789">
                    </div>
                </div>
            </div>

            <!-- Shipping Settings Tab -->
            <div id="content-shipping" class="tab-content p-4 md:p-6 hidden">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Pengaturan Pengiriman</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="shipping_jne" value="1" {{ old('shipping_jne', cache('settings.shipping_jne', true)) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">JNE</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="shipping_jnt" value="1" {{ old('shipping_jnt', cache('settings.shipping_jnt', true)) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">J&T Express</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="shipping_pos" value="1" {{ old('shipping_pos', cache('settings.shipping_pos', true)) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">Pos Indonesia</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="shipping_sicepat" value="1" {{ old('shipping_sicepat', cache('settings.shipping_sicepat', true)) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">SiCepat</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="shipping_tiki" value="1" {{ old('shipping_tiki', cache('settings.shipping_tiki', true)) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">TIKI</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="shipping_anteraja" value="1" {{ old('shipping_anteraja', cache('settings.shipping_anteraja', false)) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">AnterAja</label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Biaya Pengiriman Default (Rp)</label>
                        <input type="number" name="default_shipping_cost" value="{{ old('default_shipping_cost', cache('settings.default_shipping_cost', 15000)) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Berat Default per Produk (gram)</label>
                        <input type="number" name="default_product_weight" value="{{ old('default_product_weight', cache('settings.default_product_weight', 500)) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Pengirim</label>
                    <textarea name="sender_address" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('sender_address', cache('settings.sender_address', '')) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Alamat lengkap untuk pengiriman barang</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kota Asal</label>
                        <input type="text" name="origin_city" value="{{ old('origin_city', cache('settings.origin_city', 'Jakarta')) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pos Asal</label>
                        <input type="text" name="origin_postal_code" value="{{ old('origin_postal_code', cache('settings.origin_postal_code', '10110')) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Maintenance Tab -->
            <div id="content-maintenance" class="tab-content p-4 md:p-6 hidden">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Mode Maintenance</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="maintenance_mode" value="1" {{ old('maintenance_mode', cache('settings.maintenance_mode', false)) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">Aktifkan Mode Maintenance</label>
                    </div>
                    <p class="text-xs text-gray-500">Ketika diaktifkan, hanya admin yang dapat mengakses website</p>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pesan Maintenance</label>
                    <textarea name="maintenance_message" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('maintenance_message', cache('settings.maintenance_message', 'Website sedang dalam perbaikan. Silakan kembali lagi nanti.')) }}</textarea>
                </div>
            </div>

            <div class="border-t border-gray-200 px-4 md:px-6 py-4">
                <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
                    <button type="button" onclick="resetForm()" class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Reset
                    </button>
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                        Simpan Pengaturan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.add('active', 'border-blue-500', 'text-blue-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
}

function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset semua pengaturan?')) {
        document.querySelector('form').reset();
    }
}

// Initialize first tab as active
document.addEventListener('DOMContentLoaded', function() {
    showTab('general');
});
</script>
@endsection