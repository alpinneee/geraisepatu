@extends('layouts.app')

@section('title', 'Kebijakan Pengembalian')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Kebijakan Pengembalian</h1>
    
    <div class="prose prose-lg max-w-none">
        <div class="bg-green-50 p-6 rounded-lg mb-8">
            <h2 class="text-xl font-semibold text-green-800 mb-2">Jaminan 30 Hari</h2>
            <p class="text-green-700">Kami memberikan jaminan pengembalian 30 hari untuk semua produk yang dibeli di toko kami.</p>
        </div>
        
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Syarat Pengembalian</h2>
        <ul class="list-disc pl-6 mb-6 space-y-2">
            <li>Produk masih dalam kondisi baru dan belum digunakan</li>
            <li>Kemasan asli masih lengkap dan tidak rusak</li>
            <li>Label dan tag produk masih terpasang</li>
            <li>Disertai dengan bukti pembelian (invoice/struk)</li>
            <li>Pengembalian dilakukan maksimal 30 hari setelah pembelian</li>
        </ul>
        
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Cara Pengembalian</h2>
        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-3">1. Hubungi Customer Service</h3>
                <p class="text-gray-600 mb-3">Hubungi tim customer service kami melalui:</p>
                <ul class="text-gray-600 space-y-1">
                    <li>• WhatsApp: +62 123 4567 890</li>
                    <li>• Email: returns@tokosepatu.com</li>
                    <li>• Live Chat di website</li>
                </ul>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-3">2. Kirim Produk</h3>
                <p class="text-gray-600 mb-3">Setelah mendapat persetujuan:</p>
                <ul class="text-gray-600 space-y-1">
                    <li>• Kemas produk dengan aman</li>
                    <li>• Kirim ke alamat yang diberikan</li>
                    <li>• Simpan resi pengiriman</li>
                </ul>
            </div>
        </div>
        
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Biaya Pengembalian</h2>
        <div class="bg-blue-50 p-6 rounded-lg mb-6">
            <ul class="text-blue-800 space-y-2">
                <li><strong>GRATIS</strong> - Jika produk cacat atau salah kirim</li>
                <li><strong>Ditanggung pembeli</strong> - Jika pengembalian karena alasan pribadi</li>
                <li><strong>Pick-up service</strong> - Tersedia untuk Jakarta dengan biaya Rp 25.000</li>
            </ul>
        </div>
        
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Proses Refund</h2>
        <div class="space-y-4 mb-6">
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-semibold">1</div>
                <div>
                    <h4 class="font-semibold">Produk Diterima</h4>
                    <p class="text-gray-600">Tim kami akan memeriksa kondisi produk yang dikembalikan</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-semibold">2</div>
                <div>
                    <h4 class="font-semibold">Verifikasi</h4>
                    <p class="text-gray-600">Proses verifikasi memakan waktu 1-3 hari kerja</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-semibold">3</div>
                <div>
                    <h4 class="font-semibold">Refund Diproses</h4>
                    <p class="text-gray-600">Dana akan dikembalikan dalam 3-7 hari kerja setelah verifikasi</p>
                </div>
            </div>
        </div>
        
        <div class="bg-red-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-red-800 mb-2">Produk yang Tidak Dapat Dikembalikan:</h3>
            <ul class="text-red-700 space-y-1">
                <li>• Produk yang sudah digunakan atau kotor</li>
                <li>• Produk custom atau pesanan khusus</li>
                <li>• Produk sale/clearance (kecuali cacat)</li>
                <li>• Aksesoris seperti kaos kaki dan insole</li>
            </ul>
        </div>
    </div>
</div>
@endsection