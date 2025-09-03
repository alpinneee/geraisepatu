# Sistem Ongkos Kirim Berdasarkan Jarak dari Jakarta

## Overview
Sistem ongkos kirim telah diperbarui untuk menghitung tarif berdasarkan jarak dari Jakarta. Semakin jauh kota tujuan dari Jakarta, semakin mahal ongkos kirimnya.

## Zona Pengiriman

### 1. Jabodetabek (0-50 km dari Jakarta)
- **Tarif**: Rp 10.000
- **Kota**: Jakarta, Depok, Bogor, Tangerang, Bekasi

### 2. Jawa Barat (50-300 km dari Jakarta)  
- **Tarif**: Rp 15.000
- **Kota**: Bandung, Cimahi, Sukabumi, Garut, Tasikmalaya, Cianjur, Purwakarta

### 3. Banten (50-150 km dari Jakarta)
- **Tarif**: Rp 12.000  
- **Kota**: Serang, Cilegon, Pandeglang, Lebak

### 4. Jawa Tengah (300-600 km dari Jakarta)
- **Tarif**: Rp 20.000
- **Kota**: Semarang, Surakarta, Yogyakarta, Magelang, Purwokerto, Tegal

### 5. Jawa Timur (600-900 km dari Jakarta)
- **Tarif**: Rp 25.000
- **Kota**: Surabaya, Malang, Kediri, Blitar, Madiun, Jember, Probolinggo

### 6. Luar Pulau Jawa (1000+ km dari Jakarta)
- **Tarif**: Rp 30.000
- **Kota**: Medan, Palembang, Pekanbaru, Padang, Denpasar, Makassar, dll

## Ekspedisi Pengiriman

Setiap zona memiliki pilihan ekspedisi dengan multiplier berbeda:

1. **JNE REG** - Tarif normal (1.0x), estimasi 2-3 hari
2. **JNE YES** - Tarif premium (1.5x), estimasi 1-2 hari  
3. **J&T REG** - Tarif hemat (0.9x), estimasi 2-4 hari
4. **SiCepat REG** - Tarif termurah (0.8x), estimasi 2-3 hari

## Cara Kerja

1. User memasukkan kota tujuan di form checkout
2. Sistem mencari kota di database shipping_zones
3. Jika ditemukan, gunakan tarif zona tersebut
4. Jika tidak ditemukan, hitung berdasarkan jarak estimasi
5. Tampilkan pilihan ekspedisi dengan tarif yang sudah dikalikan multiplier

## File yang Dimodifikasi

- `app/Models/ShippingZone.php` - Logic perhitungan ongkos kirim
- `app/Http/Controllers/ShippingController.php` - API untuk calculate shipping
- `app/Http/Controllers/Customer/CheckoutController.php` - Integration dengan checkout
- `database/migrations/2025_01_20_000000_add_distance_from_jakarta_to_shipping_zones_table.php` - Migration baru
- `database/seeders/ShippingZoneSeeder.php` - Data zona pengiriman
- `resources/views/customer/checkout.blade.php` - UI checkout

## Testing

Untuk test sistem:
1. Buka halaman checkout
2. Masukkan berbagai kota (Jakarta, Bandung, Surabaya, Medan)
3. Lihat perbedaan tarif ongkos kirim
4. Pilih ekspedisi berbeda untuk melihat variasi harga

## Kustomisasi

Untuk menambah kota baru atau mengubah tarif:
1. Edit `ShippingZoneSeeder.php`
2. Atau tambah data langsung ke tabel `shipping_zones`
3. Jalankan `php artisan db:seed --class=ShippingZoneSeeder`