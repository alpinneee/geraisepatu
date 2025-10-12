<?php

use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

// Test route untuk mengirim email contact form
Route::get('/test-contact-email', function () {
    $contactData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'subject' => 'Test Contact Form',
        'message' => 'Ini adalah pesan test untuk memastikan email contact form berfungsi dengan baik.',
        'submitted_at' => now(),
    ];

    try {
        $adminEmail = config('mail.admin_email', 'admin@geraisepatu.xyz');
        Mail::to($adminEmail)->send(new ContactFormMail($contactData));
        
        return response()->json([
            'success' => true,
            'message' => 'Test email sent successfully to ' . $adminEmail,
            'data' => $contactData
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to send test email: ' . $e->getMessage(),
            'error' => $e->getTraceAsString()
        ], 500);
    }
});

// Test route untuk melihat preview email
Route::get('/preview-contact-email', function () {
    $contactData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'subject' => 'Pertanyaan tentang produk sepatu',
        'message' => 'Halo, saya ingin bertanya tentang ketersediaan sepatu Nike Air Max ukuran 42. Apakah masih ada stok? Terima kasih.',
        'submitted_at' => now(),
    ];

    return new ContactFormMail($contactData);
});