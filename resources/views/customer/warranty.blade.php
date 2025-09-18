@extends('layouts.app')

@section('title', 'Garansi Produk')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Garansi Produk</h1>
    
    <div class="prose prose-lg max-w-none">
        <div class="bg-blue-50 p-6 rounded-lg mb-8">
            <h2 class="text-xl font-semibold text-blue-800 mb-2">Komitmen Kualitas</h2>
            <p class="text-blue-700">Kami berkomitmen memberikan produk berkualitas tinggi dengan jaminan garansi untuk kepuasan pelanggan.</p>
        </div>
        
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Jenis Garansi</h2>
        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-3">Garansi Kualitas</h3>
                <ul class="space-y-2 text-gray-600">
                    <li>• <strong>6 bulan</strong> untuk sepatu branded</li>
                    <li>• <strong>3 bulan</strong> untuk sepatu lokal</li>
                    <li>• <strong>1 bulan</strong> untuk aksesoris</li>
                    <li>• Meliputi cacat produksi dan material</li>
                </ul>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-3">Garansi Keaslian</h3>
                <ul class="space-y-2 text-gray-600">
                    <li>• <strong>100% Original</strong> untuk semua brand</li>
                    <li>• Sertifikat keaslian tersedia</li>
                    <li>• Garansi seumur hidup untuk keaslian</li>
                    <li>• Uang kembali jika terbukti palsu</li>
                </ul>
            </div>
        </div>
        
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Yang Dicakup Garansi</h2>
        <div class="bg-green-50 p-6 rounded-lg mb-6">
            <ul class="text-green-800 space-y-2">
                <li>✓ Cacat jahitan atau lem</li>
                <li>✓ Kerusakan sol dalam waktu normal</li>
                <li>✓ Masalah warna yang luntur</li>
                <li>✓ Kerusakan material akibat produksi</li>
                <li>✓ Ketidaksesuaian ukuran dengan standar</li>
            </ul>
        </div>
        
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Yang Tidak Dicakup Garansi</h2>
        <div class="bg-red-50 p-6 rounded-lg mb-6">
            <ul class="text-red-800 space-y-2">
                <li>✗ Kerusakan akibat pemakaian berlebihan</li>
                <li>✗ Kerusakan akibat kelalaian pengguna</li>
                <li>✗ Keausan normal akibat pemakaian</li>
                <li>✗ Kerusakan akibat modifikasi produk</li>
                <li>✗ Kerusakan akibat cuaca ekstrem</li>
            </ul>
        </div>
        
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Cara Klaim Garansi</h2>
        <div class="space-y-4 mb-6">
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-semibold">1</div>
                <div>
                    <h4 class="font-semibold">Hubungi Customer Service</h4>
                    <p class="text-gray-600">Laporkan masalah melalui WhatsApp, email, atau datang langsung ke toko</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-semibold">2</div>
                <div>
                    <h4 class="font-semibold">Siapkan Dokumen</h4>
                    <p class="text-gray-600">Bukti pembelian, foto produk, dan deskripsi masalah</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-semibold">3</div>
                <div>
                    <h4 class="font-semibold">Evaluasi Produk</h4>
                    <p class="text-gray-600">Tim teknis akan mengevaluasi produk dalam 2-3 hari kerja</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-semibold">4</div>
                <div>
                    <h4 class="font-semibold">Penyelesaian</h4>
                    <p class="text-gray-600">Perbaikan, penggantian, atau refund sesuai kondisi</p>
                </div>
            </div>
        </div>
        
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Kontak Garansi</h2>
        <div class="bg-gray-50 p-6 rounded-lg mb-6">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-semibold mb-2">Customer Service</h4>
                    <p class="text-gray-600">WhatsApp: +62 123 4567 890</p>
                    <p class="text-gray-600">Email: warranty@tokosepatu.com</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-2">Jam Operasional</h4>
                    <p class="text-gray-600">Senin - Jumat: 09:00 - 17:00</p>
                    <p class="text-gray-600">Sabtu: 09:00 - 15:00</p>
                </div>
            </div>
        </div>
        
        <div class="bg-yellow-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-yellow-800 mb-2">Tips Perawatan:</h3>
            <ul class="text-yellow-700 space-y-1">
                <li>• Bersihkan sepatu secara rutin dengan produk yang tepat</li>
                <li>• Simpan di tempat kering dan berventilasi baik</li>
                <li>• Gunakan shoe tree untuk menjaga bentuk</li>
                <li>• Hindari pemakaian berlebihan pada kondisi ekstrem</li>
            </ul>
        </div>
    </div>
</div>
@endsection