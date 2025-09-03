<div class="bg-white p-4 rounded-lg shadow-sm border">
    <h3 class="font-semibold text-gray-800 mb-3">Hitung Ongkos Kirim</h3>
    
    <div class="space-y-3">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kota Tujuan</label>
            <select id="shipping-city" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Pilih Kota --</option>
            </select>
        </div>
        
        <div id="shipping-result" class="hidden">
            <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Ongkos Kirim ke <span id="selected-city" class="font-medium"></span>:</span>
                    <span id="shipping-cost" class="font-bold text-blue-600"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const citySelect = document.getElementById('shipping-city');
    const resultDiv = document.getElementById('shipping-result');
    const selectedCitySpan = document.getElementById('selected-city');
    const shippingCostSpan = document.getElementById('shipping-cost');

    // Load cities
    fetch('/api/shipping/cities')
        .then(response => response.json())
        .then(cities => {
            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                citySelect.appendChild(option);
            });
        });

    // Calculate shipping on city change
    citySelect.addEventListener('change', function() {
        const city = this.value;
        if (!city) {
            resultDiv.classList.add('hidden');
            return;
        }

        fetch('/api/shipping/calculate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ city: city })
        })
        .then(response => response.json())
        .then(data => {
            selectedCitySpan.textContent = data.city;
            shippingCostSpan.textContent = data.formatted_cost;
            resultDiv.classList.remove('hidden');
        });
    });
});
</script>