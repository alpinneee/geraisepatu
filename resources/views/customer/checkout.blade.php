@extends('layouts.customer')

@section('title', 'Checkout')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <h1 class="text-3xl font-bold mb-8 text-gray-900">Checkout</h1>
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Summary -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 animate-fade-in">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Ringkasan Pesanan</h2>
                
                <div class="space-y-4">
                    @foreach($cartItems as $item)
                    <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                        <img src="{{ $item->product->images->first()?->image_url ?? '/images/placeholder.jpg' }}" 
                             alt="{{ $item->product->name }}" 
                             class="w-16 h-16 object-cover rounded">
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                            <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900">
                                Rp {{ number_format($item->product->discount_price ?? $item->product->price, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Total: Rp {{ number_format(($item->product->discount_price ?? $item->product->price) * $item->quantity, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Address Form -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 animate-slide-up animation-delay-2000">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Alamat Pengiriman</h2>
                
                @if(\Illuminate\Support\Facades\Auth::check() && $shippingAddresses->count() > 0)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Alamat Tersimpan</label>
                    <select id="saved-address" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih alamat tersimpan</option>
                        @foreach($shippingAddresses as $address)
                        <option value="{{ $address->id }}" 
                                data-name="{{ $address->name }}"
                                data-phone="{{ $address->phone }}"
                                data-address="{{ $address->address }}"
                                data-city="{{ $address->city }}"
                                data-province="{{ $address->province }}"
                                data-postal-code="{{ $address->postal_code }}">
                            {{ $address->name }} - {{ $address->full_address }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <form method="POST" action="{{ route('checkout.process') }}" id="checkout-form">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Penerima *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email', \Illuminate\Support\Facades\Auth::user()?->email) }}" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon *</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="08xxxxxxxxxx">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Kota *</label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('city')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Provinsi *</label>
                            <input type="text" id="province" name="province" value="{{ old('province') }}" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('province')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Kode Pos *</label>
                            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('postal_code')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap *</label>
                        <textarea id="address" name="address" rows="3" required
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Alamat lengkap">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    @if(\Illuminate\Support\Facades\Auth::check())
                    <div class="mt-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="save_address" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Simpan alamat ini untuk pesanan selanjutnya</span>
                        </label>
                    </div>
                    @endif
            </div>

            <!-- Shipping Expedition -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 animate-slide-up animation-delay-3000">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Pilih Ekspedisi Pengiriman</h2>
                
                <!-- Info Ongkos Kirim -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <div class="font-medium text-blue-900">Ongkos Kirim Berdasarkan Jarak dari Jakarta</div>
                            <div class="text-sm text-blue-700">Semakin jauh dari Jakarta, ongkos kirim semakin mahal</div>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3">
                            <span class="font-medium text-gray-900">Pilih Ekspedisi Pengiriman</span>
                            <div class="text-sm text-gray-600 mt-1">Masukkan kota tujuan untuk melihat tarif pengiriman</div>
                        </div>
                        <div class="p-4 space-y-3" id="shipping-options">
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="shipping_expedition" value="jne_reg" class="text-blue-600 focus:ring-blue-500" data-cost="15000" data-estimation="2-3 hari">
                                <div class="ml-3 flex-1">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="font-medium text-gray-900">JNE REG (Regular)</div>
                                            <div class="text-sm text-gray-600">Estimasi: 2-3 hari kerja</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900">Rp 15.000</div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="shipping_expedition" value="jne_yes" class="text-blue-600 focus:ring-blue-500" data-cost="22000" data-estimation="1-2 hari">
                                <div class="ml-3 flex-1">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="font-medium text-gray-900">JNE YES (Yakin Esok Sampai)</div>
                                            <div class="text-sm text-gray-600">Estimasi: 1-2 hari kerja</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900">Rp 22.000</div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="shipping_expedition" value="jnt_reg" class="text-blue-600 focus:ring-blue-500" data-cost="13000" data-estimation="2-4 hari">
                                <div class="ml-3 flex-1">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="font-medium text-gray-900">J&T REG (Regular)</div>
                                            <div class="text-sm text-gray-600">Estimasi: 2-4 hari kerja</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900">Rp 13.000</div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="shipping_expedition" value="sicepat_reg" class="text-blue-600 focus:ring-blue-500" data-cost="12000" data-estimation="2-3 hari">
                                <div class="ml-3 flex-1">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="font-medium text-gray-900">SiCepat REG (Regular)</div>
                                            <div class="text-sm text-gray-600">Estimasi: 2-3 hari kerja</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900">Rp 12.000</div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                
                @error('shipping_expedition')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div class="bg-white rounded-lg shadow-md p-6 animate-slide-up animation-delay-4000">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Catatan Pesanan</h3>
                
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Catatan tambahan untuk pesanan">{{ old('notes') }}</textarea>
                </div>
                
                <!-- Payment Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <div>
                            <div class="font-medium text-blue-900">Pembayaran Online</div>
                            <div class="text-sm text-blue-700">Kartu Kredit, QRIS, E-Wallet, Bank Transfer, dan lainnya</div>
                        </div>
                    </div>
                </div>
                
                <!-- Hidden payment method field -->
                <input type="hidden" name="payment_method" value="midtrans">
                </form>
            </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4 animate-slide-up animation-delay-4000">
                <h2 class="text-xl font-semibold mb-4 text-gray-900">Total Pembayaran</h2>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($discount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Diskon</span>
                        <span>- Rp {{ number_format($discount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ongkos Kirim</span>
                        <span class="font-medium" id="shipping-cost">Rp 0</span>
                    </div>
                    

                    
                    <hr class="my-3">
                    
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span id="total-amount">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                @if($coupon)
                <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <div class="font-medium text-green-800">Kupon Terpakai</div>
                            <div class="text-sm text-green-600">{{ $coupon->code }} - {{ $coupon->description }}</div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Selected Payment & Shipping Info -->
                <div id="selected-info" class="mb-4" style="display: none;">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="text-sm text-blue-800">
                            <div id="selected-payment" class="font-medium"></div>
                            <div id="selected-shipping" class="mt-1"></div>
                        </div>
                    </div>
                </div>
                
                <button type="button" onclick="processCheckout()"
                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200"
                        id="checkout-btn">
                    Proses Checkout
                </button>
                
                <!-- Midtrans Snap JS -->
                <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
                
                <div class="mt-4 text-center">
                    <a href="{{ route('cart.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        ← Kembali ke Keranjang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const savedAddressSelect = document.getElementById('saved-address');
    const nameInput = document.getElementById('name');
    const phoneInput = document.getElementById('phone');
    const addressInput = document.getElementById('address');
    const cityInput = document.getElementById('city');
    const provinceInput = document.getElementById('province');
    const postalCodeInput = document.getElementById('postal_code');
    
    // Shipping tracking
    const shippingCostElement = document.getElementById('shipping-cost');
    const totalAmountElement = document.getElementById('total-amount');
    const selectedInfo = document.getElementById('selected-info');
    const selectedPayment = document.getElementById('selected-payment');
    const selectedShipping = document.getElementById('selected-shipping');
    const checkoutBtn = document.getElementById('checkout-btn');
    
    let currentShippingCost = 0;
    const baseTotal = {{ $total }};
    
    // Handle saved address selection
    if (savedAddressSelect) {
        savedAddressSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                nameInput.value = selectedOption.dataset.name;
                phoneInput.value = selectedOption.dataset.phone;
                addressInput.value = selectedOption.dataset.address;
                cityInput.value = selectedOption.dataset.city;
                provinceInput.value = selectedOption.dataset.province;
                postalCodeInput.value = selectedOption.dataset.postalCode || selectedOption.getAttribute('data-postal-code');
                
                // Auto calculate shipping for selected city
                if (selectedOption.dataset.city) {
                    updateShippingOptions(selectedOption.dataset.city);
                }
            }
        });
    }
    
    // Calculate shipping when city changes
    if (cityInput) {
        cityInput.addEventListener('blur', function() {
            if (this.value.trim()) {
                updateShippingOptions(this.value.trim());
            }
        });
        
        cityInput.addEventListener('input', function() {
            if (this.value.trim().length > 3) {
                updateShippingOptions(this.value.trim());
            }
        });
    }
    
    // Function to update shipping options based on city
    function updateShippingOptions(city) {
        const shippingContainer = document.getElementById('shipping-options');
        if (!shippingContainer) return;
        
        // Determine shipping cost based on city
        let baseCost = getShippingCostByCity(city);
        
        // Clear existing options
        shippingContainer.innerHTML = '';
        
        // Add shipping info
        const infoHtml = `
            <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    <div>
                        <div class="font-medium text-green-800">Pengiriman ke ${city}</div>
                        <div class="text-sm text-green-600">Ongkos kirim berdasarkan jarak dari Jakarta</div>
                    </div>
                </div>
            </div>
        `;
        shippingContainer.insertAdjacentHTML('beforeend', infoHtml);
        
        // Add shipping options with different multipliers
        const expeditions = [
            {code: 'jne_reg', name: 'JNE REG', multiplier: 1.0, estimation: '2-3 hari'},
            {code: 'jne_yes', name: 'JNE YES', multiplier: 1.5, estimation: '1-2 hari'},
            {code: 'jnt_reg', name: 'J&T REG', multiplier: 0.9, estimation: '2-4 hari'},
            {code: 'sicepat_reg', name: 'SiCepat REG', multiplier: 0.8, estimation: '2-3 hari'}
        ];
        
        expeditions.forEach(expedition => {
            const cost = Math.round(baseCost * expedition.multiplier);
            const optionHtml = `
                <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                    <input type="radio" name="shipping_expedition" value="${expedition.code}" class="text-blue-600 focus:ring-blue-500" data-cost="${cost}" data-estimation="${expedition.estimation}">
                    <div class="ml-3 flex-1">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="font-medium text-gray-900">${expedition.name}</div>
                                <div class="text-sm text-gray-600">Estimasi: ${expedition.estimation}</div>
                            </div>
                            <div class="text-right">
                                <div class="font-medium text-gray-900">Rp ${cost.toLocaleString('id-ID')}</div>
                            </div>
                        </div>
                    </div>
                </label>
            `;
            shippingContainer.insertAdjacentHTML('beforeend', optionHtml);
        });
        
        // Re-attach event listeners for new radio buttons
        attachShippingListeners();
    }
    
    // Function to get shipping cost by city
    function getShippingCostByCity(city) {
        const cityLower = city.toLowerCase().replace(/kota |kabupaten /g, '');
        
        // Jabodetabek - Terdekat dari Jakarta
        if (['jakarta', 'depok', 'bogor', 'tangerang', 'bekasi', 'jakarta pusat', 'jakarta utara', 'jakarta selatan', 'jakarta barat', 'jakarta timur'].some(c => cityLower.includes(c))) {
            return 8000;
        }
        
        // Jawa Barat - Dekat
        if (['bandung', 'cimahi', 'sukabumi', 'garut', 'tasikmalaya', 'cianjur', 'cirebon', 'karawang', 'purwakarta', 'subang'].some(c => cityLower.includes(c))) {
            return 12000;
        }
        
        // Banten - Dekat
        if (['serang', 'cilegon', 'pandeglang', 'lebak'].some(c => cityLower.includes(c))) {
            return 10000;
        }
        
        // Jawa Tengah - Sedang
        if (['semarang', 'surakarta', 'yogyakarta', 'magelang', 'purwokerto', 'solo', 'salatiga', 'tegal', 'pekalongan', 'kudus'].some(c => cityLower.includes(c))) {
            return 18000;
        }
        
        // Jawa Timur - Sedang
        if (['surabaya', 'malang', 'kediri', 'blitar', 'madiun', 'jember', 'mojokerto', 'pasuruan', 'probolinggo', 'sidoarjo'].some(c => cityLower.includes(c))) {
            return 22000;
        }
        
        // Sumatra - Jauh
        if (['medan', 'palembang', 'pekanbaru', 'padang', 'bandar lampung', 'jambi', 'bengkulu', 'banda aceh'].some(c => cityLower.includes(c))) {
            return 35000;
        }
        
        // Kalimantan - Jauh
        if (['pontianak', 'banjarmasin', 'samarinda', 'balikpapan', 'palangkaraya'].some(c => cityLower.includes(c))) {
            return 40000;
        }
        
        // Sulawesi - Jauh
        if (['makassar', 'manado', 'palu', 'kendari', 'gorontalo'].some(c => cityLower.includes(c))) {
            return 45000;
        }
        
        // Papua & Maluku - Sangat Jauh
        if (['jayapura', 'sorong', 'merauke', 'ambon', 'ternate'].some(c => cityLower.includes(c))) {
            return 60000;
        }
        
        // Bali & Nusa Tenggara - Sedang
        if (['denpasar', 'mataram', 'kupang', 'bali'].some(c => cityLower.includes(c))) {
            return 25000;
        }
        
        // Default untuk kota lainnya
        return 30000;
    }
    
    // Function to attach shipping listeners
    function attachShippingListeners() {
        const shippingRadios = document.querySelectorAll('input[name="shipping_expedition"]');
        shippingRadios.forEach(radio => {
            radio.addEventListener('change', handleShippingChange);
        });
    }
    
    // Handle shipping expedition selection
    function handleShippingChange() {
        if (this.checked) {
            currentShippingCost = parseInt(this.dataset.cost);
            shippingCostElement.textContent = 'Rp ' + currentShippingCost.toLocaleString('id-ID');
            selectedShipping.textContent = this.parentElement.querySelector('.font-medium').textContent + ' - ' + this.dataset.estimation;
            updateTotal();
            updateSelectedInfo();
        }
    }
    
    // Initial attachment
    attachShippingListeners();
    
    // Legacy code for backward compatibility
    const shippingRadios = document.querySelectorAll('input[name="shipping_expedition"]');
    shippingRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                currentShippingCost = parseInt(this.dataset.cost);
                shippingCostElement.textContent = 'Rp ' + currentShippingCost.toLocaleString('id-ID');
                selectedShipping.textContent = this.parentElement.querySelector('.font-medium').textContent + ' - ' + this.dataset.estimation;
                updateTotal();
                updateSelectedInfo();
            }
        });
    });
    
    // Initialize payment method (Midtrans only)
    selectedPayment.textContent = 'Pembayaran Online (Midtrans)';
    
    function updateTotal() {
        const newTotal = baseTotal + currentShippingCost;
        totalAmountElement.textContent = 'Rp ' + newTotal.toLocaleString('id-ID');
    }
    
    function updateSelectedInfo() {
        const hasShipping = document.querySelector('input[name="shipping_expedition"]:checked');
        
        if (hasShipping) {
            selectedInfo.style.display = 'block';
        } else {
            selectedInfo.style.display = 'none';
        }
    }
    
    // Checkout button click handler
    window.processCheckout = function() {
        try {
            console.log('Checkout button clicked');
            console.log('Current shipping cost:', currentShippingCost);
            console.log('Base total:', baseTotal);
            
            const form = document.getElementById('checkout-form');
            if (!form) {
                console.error('Form not found!');
                alert('Form tidak ditemukan. Silakan refresh halaman.');
                return;
            }
            
            // Validate required fields
            const requiredFields = {
                'name': 'Nama Penerima',
                'email': 'Email',
                'phone': 'No. Telepon',
                'address': 'Alamat Lengkap',
                'city': 'Kota',
                'province': 'Provinsi',
                'postal_code': 'Kode Pos'
            };
            let missingFields = [];
            
            Object.keys(requiredFields).forEach(field => {
                const input = form.querySelector(`[name="${field}"]`);
                if (!input || !input.value.trim()) {
                    missingFields.push(requiredFields[field]);
                }
            });
            
            if (missingFields.length > 0) {
                alert('Silakan lengkapi field berikut:\n• ' + missingFields.join('\n• '));
                return;
            }
            
            const hasShipping = document.querySelector('input[name="shipping_expedition"]:checked');
            
            if (!hasShipping) {
                alert('Silakan pilih ekspedisi pengiriman terlebih dahulu');
                return;
            }
            
            // Validate email format
            const emailInput = form.querySelector('[name="email"]');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailInput && !emailRegex.test(emailInput.value)) {
                alert('Format email tidak valid');
                emailInput.focus();
                return;
            }
            
            // Validate phone format
            const phoneInput = form.querySelector('[name="phone"]');
            if (phoneInput && phoneInput.value.length < 10) {
                alert('Nomor telepon minimal 10 digit');
                phoneInput.focus();
                return;
            }
            
            if (!currentShippingCost || currentShippingCost < 1000) {
                // Auto-select first shipping option if none selected
                const firstShippingOption = document.querySelector('input[name="shipping_expedition"]');
                if (firstShippingOption) {
                    firstShippingOption.checked = true;
                    currentShippingCost = parseInt(firstShippingOption.dataset.cost);
                    updateTotal();
                    updateSelectedInfo();
                } else {
                    alert('Silakan pilih ekspedisi pengiriman.');
                    return;
                }
            }
            
            console.log('Validation passed. Shipping cost:', currentShippingCost);
            
            // Show loading state
            const submitBtn = document.getElementById('checkout-btn');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '⏳ Memproses...';
            }
            
            // Prepare form data
            const formData = new FormData(form);
            
            // Ensure shipping expedition is included
            const selectedShipping = document.querySelector('input[name="shipping_expedition"]:checked');
            if (selectedShipping) {
                formData.set('shipping_expedition', selectedShipping.value);
            }
            
            formData.set('shipping_cost', currentShippingCost || 15000);
            formData.set('cod_fee', 0);
            formData.set('payment_method', 'midtrans');
            
            console.log('Form data being sent:');
            console.log('Shipping cost:', currentShippingCost || 15000);
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }
            
            // Send AJAX request to create order and get snap token
            fetch('{{ route("checkout.process") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Server response:', text);
                        try {
                            const data = JSON.parse(text);
                            if (response.status === 422 && data.errors) {
                                let errorMsg = 'Silakan periksa data berikut:\n';
                                Object.keys(data.errors).forEach(key => {
                                    const fieldName = {
                                        'name': 'Nama',
                                        'email': 'Email',
                                        'phone': 'Telepon',
                                        'address': 'Alamat',
                                        'city': 'Kota',
                                        'province': 'Provinsi',
                                        'postal_code': 'Kode Pos',
                                        'shipping_expedition': 'Ekspedisi Pengiriman'
                                    }[key] || key;
                                    errorMsg += `• ${fieldName}: ${data.errors[key].join(', ')}\n`;
                                });
                                throw new Error(errorMsg);
                            }
                            throw new Error(data.message || `Server error: ${response.status}`);
                        } catch (parseError) {
                            if (parseError.message.includes('Silakan periksa')) {
                                throw parseError;
                            }
                            console.error('Parse error:', parseError);
                            throw new Error(`Server error ${response.status}: ${text.substring(0, 200)}`);
                        }
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.success && data.snap_token) {
                    // Show Midtrans popup
                    snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            console.log('Payment success:', result);
                            alert('Pembayaran berhasil!');
                            window.location.href = '{{ route("checkout.success") }}';
                        },
                        onPending: function(result) {
                            console.log('Payment pending:', result);
                            alert('Pembayaran sedang diproses. Silakan tunggu konfirmasi.');
                            window.location.href = '{{ route("checkout.success") }}';
                        },
                        onError: function(result) {
                            console.log('Payment error:', result);
                            alert('Pembayaran gagal. Anda dapat melanjutkan pembayaran nanti dari halaman pesanan.');
                            window.location.href = '{{ route("profile.orders") }}';
                        },
                        onClose: function() {
                            console.log('Payment popup closed');
                        }
                    });
                } else {
                    console.error('Server error:', data);
                    alert(data.message || 'Terjadi kesalahan saat memproses pesanan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error: ' + error.message);
            })
            .finally(() => {
                // Re-enable button
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Proses Checkout';
                }
            });
            
        } catch (error) {
            console.error('Error in checkout process:', error);
            alert('Terjadi kesalahan: ' + error.message);
            
            // Re-enable button
            const submitBtn = document.getElementById('checkout-btn');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Proses Checkout';
            }
        }
    };
    

    
    // Initialize values for pre-selected options on page load
    function initializeSelectedValues() {
        // Check for pre-selected shipping
        const selectedShippingRadio = document.querySelector('input[name="shipping_expedition"]:checked');
        if (selectedShippingRadio) {
            currentShippingCost = parseInt(selectedShippingRadio.dataset.cost);
            shippingCostElement.textContent = 'Rp ' + currentShippingCost.toLocaleString('id-ID');
            const shippingText = selectedShippingRadio.parentElement.querySelector('.font-medium').textContent + ' - ' + selectedShippingRadio.dataset.estimation;
            selectedShipping.textContent = shippingText;
            console.log('Pre-selected shipping found:', selectedShippingRadio.value, 'cost:', currentShippingCost);
        }
        
        // Set payment method to Midtrans
        selectedPayment.textContent = 'Pembayaran Online (Midtrans)';
        
        updateTotal();
        updateSelectedInfo();
    }
    
    // Call initialization
    initializeSelectedValues();
});
</script>
@endsection 