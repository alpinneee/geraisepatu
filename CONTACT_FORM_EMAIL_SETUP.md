# Setup Email Contact Form - KickVerse

## Overview
Implementasi lengkap untuk mengirim email ke admin ketika ada form contact yang disubmit di website https://geraisepatu.xyz/contact

## Fitur yang Diimplementasikan

### 1. Email Notification ke Admin
- ✅ Email otomatis dikirim ke admin ketika form contact disubmit
- ✅ Template email yang profesional dan informatif
- ✅ Reply-to address menggunakan email pengirim
- ✅ Error handling dan logging

### 2. Database Storage
- ✅ Data contact disimpan ke database untuk tracking
- ✅ Status read/unread dan replied
- ✅ Timestamp untuk audit trail

### 3. Admin Panel Management
- ✅ Dashboard admin untuk melihat semua contact messages
- ✅ Filter berdasarkan status (unread/read/replied)
- ✅ Search functionality
- ✅ Bulk actions (mark as read, delete)
- ✅ Detail view dengan quick reply

## Files yang Dibuat/Dimodifikasi

### 1. Email Components
- `app/Mail/ContactFormMail.php` - Mailable class untuk email
- `resources/views/emails/contact-form.blade.php` - Template email

### 2. Database
- `app/Models/Contact.php` - Model untuk contact messages
- `database/migrations/xxxx_create_contacts_table.php` - Migration

### 3. Controllers
- `app/Http/Controllers/Customer/HomeController.php` - Updated submitContact method
- `app/Http/Controllers/Admin/ContactController.php` - Admin contact management

### 4. Views
- `resources/views/admin/contacts/index.blade.php` - Admin contact list
- `resources/views/admin/contacts/show.blade.php` - Admin contact detail
- `resources/views/customer/contact.blade.php` - Updated with error handling

### 5. Configuration
- `.env` - Added MAIL_ADMIN_EMAIL
- `config/mail.php` - Added admin_email config
- `routes/web.php` - Added admin contact routes

### 6. Testing
- `routes/test-email.php` - Test routes untuk email

## Konfigurasi Email

### Environment Variables (.env)
```env
MAIL_MAILER=resend
MAIL_HOST=smtp.resend.com
MAIL_PORT=587
MAIL_USERNAME=resend
MAIL_PASSWORD=re_WEStXXvh_3GVQEvXZqz2md1QVdLHKN9Vj
MAIL_FROM_ADDRESS="noreply@geraisepatu.xyz"
MAIL_FROM_NAME="KickVerse"
MAIL_ENCRYPTION=tls
MAIL_ADMIN_EMAIL="admin@geraisepatu.xyz"

RESEND_API_KEY=re_WEStXXvh_3GVQEvXZqz2md1QVdLHKN9Vj
```

## Cara Penggunaan

### 1. Setup Database
```bash
php artisan migrate
```

### 2. Test Email Functionality
Kunjungi URL berikut untuk test:
- Preview email: `http://localhost:8000/preview-contact-email`
- Send test email: `http://localhost:8000/test-contact-email`

### 3. Admin Panel
Akses admin panel di: `http://localhost:8000/admin/contacts`

## Flow Proses

1. **User mengisi form contact** di `/contact`
2. **Data divalidasi** oleh Laravel validation
3. **Data disimpan** ke database (tabel `contacts`)
4. **Email dikirim** ke admin menggunakan Resend
5. **User mendapat konfirmasi** bahwa pesan berhasil dikirim
6. **Admin dapat melihat** dan mengelola pesan di admin panel

## Email Template Features

- **Professional design** dengan styling yang clean
- **Contact information** lengkap (nama, email, subjek, pesan)
- **Timestamp** kapan pesan diterima
- **Reply-to header** untuk memudahkan admin membalas
- **Responsive design** untuk berbagai device

## Admin Panel Features

### Dashboard Contact Messages
- **List semua contact messages** dengan pagination
- **Filter berdasarkan status**: All, Unread, Read
- **Search functionality** berdasarkan nama, email, atau subjek
- **Bulk actions**: Mark as read, Delete selected
- **Status indicators**: Unread (warning), Read (info), Replied (success)

### Detail Contact Message
- **Full message content** dengan formatting yang baik
- **Contact information** lengkap
- **Quick reply** via email client
- **Mark as replied** functionality
- **Delete message** option

## Error Handling

- **Email sending failures** dicatat di log
- **User-friendly error messages** jika email gagal dikirim
- **Fallback mechanism** tetap menyimpan data ke database meski email gagal
- **Validation errors** ditampilkan dengan jelas

## Security Considerations

- **CSRF protection** pada semua form
- **Input validation** dan sanitization
- **Email rate limiting** (bisa ditambahkan jika diperlukan)
- **Admin authentication** required untuk akses contact management

## Customization Options

### 1. Email Template
Edit `resources/views/emails/contact-form.blade.php` untuk mengubah design email.

### 2. Admin Email
Ubah `MAIL_ADMIN_EMAIL` di `.env` untuk mengubah email tujuan.

### 3. Email Subject
Modify di `ContactFormMail.php` method `envelope()`.

### 4. Validation Rules
Update di `HomeController.php` method `submitContact()`.

## Monitoring & Analytics

### Database Queries untuk Monitoring
```sql
-- Total contact messages
SELECT COUNT(*) FROM contacts;

-- Unread messages
SELECT COUNT(*) FROM contacts WHERE is_read = 0;

-- Messages by date
SELECT DATE(created_at) as date, COUNT(*) as count 
FROM contacts 
GROUP BY DATE(created_at) 
ORDER BY date DESC;

-- Response rate
SELECT 
  COUNT(*) as total,
  SUM(CASE WHEN replied_at IS NOT NULL THEN 1 ELSE 0 END) as replied,
  ROUND(SUM(CASE WHEN replied_at IS NOT NULL THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) as response_rate
FROM contacts;
```

## Troubleshooting

### Email tidak terkirim
1. Check `.env` configuration
2. Verify Resend API key
3. Check Laravel logs: `storage/logs/laravel.log`
4. Test dengan route: `/test-contact-email`

### Database error
1. Run migration: `php artisan migrate`
2. Check database connection
3. Verify table exists: `contacts`

### Admin panel tidak bisa diakses
1. Pastikan user sudah login sebagai admin
2. Check routes: `php artisan route:list | grep contact`
3. Verify middleware authentication

## Future Enhancements

1. **Email templates** yang bisa dikustomisasi dari admin panel
2. **Auto-reply** email untuk user
3. **Email notifications** untuk admin (real-time)
4. **Contact form** dengan attachment support
5. **Integration** dengan CRM systems
6. **Analytics dashboard** untuk contact metrics
7. **Email queue** untuk better performance

## Support

Jika ada pertanyaan atau issue, silakan check:
1. Laravel documentation untuk Mail
2. Resend documentation
3. Log files di `storage/logs/`
4. Test routes untuk debugging