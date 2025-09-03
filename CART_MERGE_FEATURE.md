# Fitur Merge Cart Guest ke User

## Deskripsi
Fitur ini memungkinkan keranjang belanja yang dibuat sebelum login (guest cart) tetap tersimpan dan digabungkan dengan keranjang user setelah login.

## Cara Kerja

### 1. Guest Cart (Sebelum Login)
- Produk yang ditambahkan ke keranjang disimpan di session dengan key 'cart'
- Format data: array berisi product_id, size, dan quantity

### 2. Setelah Login
- Event listener `MergeGuestCart` akan otomatis dijalankan saat user login
- Listener akan mengambil data cart dari session
- Data cart guest akan digabungkan dengan cart user di database
- Jika produk yang sama sudah ada di cart user, quantity akan ditambahkan
- Jika produk belum ada, akan dibuat entry baru di database
- Session cart akan dihapus setelah merge selesai

### 3. Validasi
- Produk harus masih aktif (is_active = true)
- Stock produk harus mencukupi
- Jika validasi gagal, item tidak akan ditambahkan

## File yang Dimodifikasi

1. **app/Listeners/MergeGuestCart.php** - Event listener untuk merge cart
2. **app/Providers/EventServiceProvider.php** - Registrasi event listener
3. **app/Services/CartService.php** - Menambahkan method mergeGuestCart
4. **routes/cart-test.php** - Route untuk testing fitur

## Testing

Gunakan route berikut untuk testing:
- `/test-cart-merge` - Membuat guest cart
- `/test-login-merge` - Simulasi login dan merge
- `/test-cart-status` - Cek status cart

## Implementasi

Fitur ini bekerja otomatis tanpa perlu konfigurasi tambahan. Setiap kali user login, sistem akan otomatis menggabungkan cart guest dengan cart user.