# Sistem Ongkos Kirim Berbasis Depok

## Overview
Sistem ongkos kirim ini dirancang khusus untuk toko sepatu yang berlokasi di Depok. Ongkos kirim dihitung berdasarkan zona geografis dan berat paket, memberikan tarif yang adil dan transparan untuk pelanggan.

## Zona Pengiriman

### Zona 1 (Multiplier: 1.0x) - Jabodetabek
- Jakarta
- Depok
- Bogor
- Tangerang
- Bekasi

### Zona 2 (Multiplier: 1.5x) - Jawa Barat Sekitar
- Bandung
- Cirebon
- Sukabumi
- Cianjur
- Purwakarta

### Zona 3 (Multiplier: 2.0x) - Jawa Tengah
- Semarang
- Yogyakarta
- Solo
- Magelang
- Purwokerto

### Zona 4 (Multiplier: 2.5x) - Jawa Timur
- Surabaya
- Malang
- Kediri
- Madiun
- Jember

### Zona 5 (Multiplier: 3.0x) - Sumatera
- Medan
- Palembang
- Pekanbaru
- Padang
- Jambi

### Zona 6 (Multiplier: 3.5x) - Sulawesi
- Makassar
- Manado
- Palu
- Kendari
- Gorontalo

### Zona 7 (Multiplier: 4.0x) - Indonesia Timur
- Denpasar
- Mataram
- Kupang
- Ambon
- Jayapura

## Layanan Pengiriman

### 1. Regular Service
- **Tarif Dasar**: Rp 8.000
- **Estimasi**: 1-5 hari kerja (tergantung zona)
- **Tersedia**: Semua zona

### 2. Express Service
- **Tarif Dasar**: Rp 12.000
- **Estimasi**: 1-4 hari kerja (tergantung zona)
- **Tersedia**: Semua zona

### 3. Same Day Service
- **Tarif Dasar**: Rp 20.000
- **Estimasi**: 4-8 jam
- **Tersedia**: Hanya Zona 1 (Jabodetabek)

## Perhitungan Ongkos Kirim

### Formula
```
Ongkos Kirim = Tarif Dasar × Zona Multiplier × Weight Multiplier
```

### Weight Multiplier
- Minimum 1kg (untuk paket di bawah 1kg tetap dihitung 1kg)
- Setiap kilogram tambahan dihitung penuh
- Contoh: 1.5kg = 2kg (dibulatkan ke atas)

### Contoh Perhitungan

#### Contoh 1: Jakarta (Zona 1) - 1kg - Regular
```
Ongkos Kirim = Rp 8.000 × 1.0 × 1 = Rp 8.000
```

#### Contoh 2: Bandung (Zona 2) - 1.5kg - Express
```
Weight Multiplier = ceil(1.5) = 2
Ongkos Kirim = Rp 12.000 × 1.5 × 2 = Rp 36.000
```

#### Contoh 3: Surabaya (Zona 4) - 2kg - Regular
```
Ongkos Kirim = Rp 8.000 × 2.5 × 2 = Rp 40.000
```

## Implementasi Teknis

### File Service
- `app/Services/DepokShippingService.php` - Service utama untuk perhitungan ongkos kirim

### Controller Integration
- `app/Http/Controllers/Customer/CheckoutController.php` - Integrasi dengan proses checkout

### Frontend Integration
- `resources/views/customer/checkout.blade.php` - UI untuk pemilihan ekspedisi
- JavaScript untuk kalkulasi real-time dan informasi zona

### Testing Routes
- `/test-shipping/{city}` - Test ongkos kirim untuk kota tertentu
- `/test-shipping-multiple` - Test ongkos kirim untuk multiple kota

## Fitur Utama

### 1. Real-time Calculation
- Ongkos kirim dihitung otomatis saat customer memasukkan kota tujuan
- Informasi zona ditampilkan secara real-time

### 2. Zone Information
- Customer dapat melihat zona pengiriman untuk kota mereka
- Transparansi dalam perhitungan tarif

### 3. Service Options
- Multiple pilihan layanan (Regular, Express, Same Day)
- Estimasi waktu pengiriman yang akurat

### 4. Weight-based Pricing
- Perhitungan berdasarkan berat aktual produk
- Fair pricing untuk customer

## Konfigurasi

### Environment Variables
Tidak ada konfigurasi khusus diperlukan. Service menggunakan data statis yang dapat dimodifikasi di dalam class `DepokShippingService`.

### Customization
Untuk mengubah zona atau tarif:
1. Edit method `initializeZones()` untuk mengubah zona
2. Edit method `initializeBaseRates()` untuk mengubah tarif dasar
3. Modify multiplier di array `$zones` untuk mengubah tarif zona

## Testing

### Manual Testing
```bash
# Test single city
curl http://localhost:8000/test-shipping/jakarta

# Test multiple cities
curl http://localhost:8000/test-shipping-multiple
```

### Expected Response Format
```json
{
    "destination_city": "jakarta",
    "weight": "1500g",
    "zone_info": {
        "zone": "zone_1",
        "multiplier": 1,
        "cities": ["jakarta", "depok", "bogor", "tangerang", "bekasi"]
    },
    "all_services": {
        "regular": {
            "name": "Regular",
            "cost": 12000,
            "estimation": "1-2 hari",
            "code": "regular"
        }
    }
}
```

## Maintenance

### Adding New Cities
1. Tambahkan kota baru ke zona yang sesuai di method `initializeZones()`
2. Test dengan route testing untuk memastikan zona detection bekerja

### Updating Rates
1. Modify `initializeBaseRates()` untuk tarif dasar
2. Modify multiplier di `initializeZones()` untuk tarif zona
3. Test dengan berbagai kombinasi kota dan berat

### Performance Considerations
- Service menggunakan data statis untuk performa optimal
- Tidak ada API call eksternal yang dapat memperlambat checkout
- Caching tidak diperlukan karena perhitungan sangat cepat

## Migration dari System Lama

Jika sebelumnya menggunakan JNE/Shipper API:
1. Service lama tetap tersedia sebagai fallback
2. Checkout controller sudah diupdate untuk menggunakan DepokShippingService
3. Frontend sudah diupdate untuk menampilkan service baru
4. Backward compatibility terjaga untuk order yang sudah ada

## Support

Untuk pertanyaan atau issue terkait sistem ongkos kirim:
1. Check log Laravel untuk error
2. Test dengan route testing untuk debugging
3. Verify zona detection dengan berbagai input kota
4. Check JavaScript console untuk frontend issues