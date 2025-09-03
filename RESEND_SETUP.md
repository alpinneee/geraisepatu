# Resend Setup untuk Forgot Password

## Langkah-langkah Setup:

### 1. Dapatkan API Key dari Resend
1. Kunjungi [resend.com](https://resend.com)
2. Daftar atau login ke akun Anda
3. Buat API Key baru di dashboard
4. Copy API Key tersebut

### 2. Update .env File
Ganti nilai berikut di file `.env`:

```env
MAIL_MAILER=resend
MAIL_PASSWORD=your_actual_resend_api_key_here
MAIL_FROM_ADDRESS=noreply@yourdomain.com
RESEND_API_KEY=your_actual_resend_api_key_here
```

**Penting:**
- Ganti `your_actual_resend_api_key_here` dengan API key yang sebenarnya dari Resend
- Ganti `noreply@yourdomain.com` dengan domain yang sudah diverifikasi di Resend
- Jika belum punya domain, gunakan domain sandbox dari Resend

### 3. Verifikasi Domain (Opsional untuk Production)
Untuk production, Anda perlu memverifikasi domain di dashboard Resend dengan menambahkan DNS records.

### 4. Test Forgot Password
1. Jalankan `php artisan serve`
2. Kunjungi `http://127.0.0.1:8000/forgot-password`
3. Masukkan email yang terdaftar
4. Cek email untuk link reset password

## Troubleshooting:

### Jika email tidak terkirim:
1. Pastikan API key benar
2. Pastikan domain sudah diverifikasi (untuk production)
3. Cek log Laravel: `storage/logs/laravel.log`
4. Untuk testing, bisa gunakan domain sandbox Resend

### Untuk Development/Testing:
Resend menyediakan domain sandbox untuk testing tanpa perlu verifikasi domain.