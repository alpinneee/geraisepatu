@extends('layouts.app')

@section('title', 'Kebijakan Pengiriman')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Kebijakan Pengiriman</h1>
    
    <div class="prose prose-lg max-w-none">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Metode Pengiriman</h2>
        <p class="mb-6">Kami bekerja sama dengan berbagai kurir terpercaya untuk memastikan produk Anda sampai dengan aman dan tepat waktu.</p>
        
        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-3">Pengiriman Reguler</h3>
                <ul class="space-y-2 text-gray-600">
                    <li>• JNE Regular (2-3 hari kerja)</li>
                    <li>• J&T Express (2-4 hari kerja)</li>
                    <li>• SiCepat (2-3 hari kerja)</li>
                    <li>• AnterAja (2-4 hari kerja)</li>
                </ul>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-3">Pengiriman Express</h3>
                <ul class="space-y-2 text-gray-600">
                    <li>• JNE YES (1-2 hari kerja)</li>
                    <li>• J&T Express Super (1 hari kerja)</li>
                    <li>• SiCepat HALU (1 hari kerja)</li>
                    <li>• Same Day Delivery (Jakarta)</li>
                </ul>
            </div>
        </div>
        
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Biaya Pengiriman</h2>
        <div class="bg-blue-50 p-6 rounded-lg mb-6">
            <p class="text-blue-800">
                <strong>GRATIS ONGKIR</strong> untuk pembelian minimal Rp 500.000 ke seluruh Indonesia!
            </p>
        </div>
        
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Waktu Pemrosesan</h2>
        <ul class="list-disc pl-6 mb-6 space-y-2">
            <li>Pesanan akan diproses dalam 1-2 hari kerja setelah pembayaran dikonfirmasi</li>
            <li>Untuk produk pre-order, waktu pemrosesan 3-7 hari kerja</li>
            <li>Pesanan yang masuk pada hari Sabtu-Minggu akan diproses pada hari kerja berikutnya</li>
        </ul>
        
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Tracking Pengiriman</h2>
        <p class="mb-4">Setelah pesanan dikirim, Anda akan menerima:</p>
        <ul class="list-disc pl-6 mb-6 space-y-2">
            <li>Email konfirmasi pengiriman dengan nomor resi</li>
            <li>SMS notifikasi dengan link tracking</li>
            <li>Update status pengiriman di akun Anda</li>
        </ul>
        
        <div class="bg-yellow-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-yellow-800 mb-2">Catatan Penting:</h3>
            <ul class="text-yellow-700 space-y-1">
                <li>• Pastikan alamat pengiriman lengkap dan benar</li>
                <li>• Waktu pengiriman dapat berbeda tergantung lokasi dan kondisi cuaca</li>
                <li>• Untuk area terpencil, waktu pengiriman mungkin lebih lama</li>
            </ul>
        </div>
    </div>
</div>
@endsection