@extends('layouts.app')

@section('title', 'Kebijakan Privasi')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Kebijakan Privasi</h1>
    
    <div class="prose prose-lg max-w-none">
        <div class="bg-blue-50 p-6 rounded-lg mb-8">
            <p class="text-blue-800"><strong>Terakhir diperbarui:</strong> {{ date('d F Y') }}</p>
            <p class="text-blue-700 mt-2">Kami menghargai privasi Anda dan berkomitmen untuk melindungi data pribadi yang Anda berikan kepada kami.</p>
        </div>
        
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Informasi yang Kami Kumpulkan</h2>
        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-3">Data Pribadi</h3>
                <ul class="space-y-2 text-gray-600">
                    <li>• Nama lengkap</li>
                    <li>• Alamat email</li>
                    <li>• Nomor telepon</li>
                    <li>• Alamat pengiriman</li>
                    <li>• Tanggal lahir (opsional)</li>
                </ul>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-3">Data Transaksi</h3>
                <ul class="space-y-2 text-gray-600">
                    <li>• Riwayat pembelian</li>
                    <li>• Metode pembayaran</li>
                    <li>• Preferensi produk</li>
                    <li>• Data pengiriman</li>
                    <li>• Komunikasi dengan CS</li>
                </ul>
            </div>
        </div>
        
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Bagaimana Kami Menggunakan Data</h2>
        <ul class="list-disc pl-6 mb-6 space-y-2">
            <li>Memproses dan mengirim pesanan Anda</li>
            <li>Memberikan layanan customer service</li>
            <li>Mengirim notifikasi terkait pesanan</li>
            <li>Meningkatkan pengalaman berbelanja</li>
            <li>Mengirim penawaran dan promosi (dengan persetujuan)</li>
            <li>Mencegah penipuan dan aktivitas ilegal</li>
        </ul>
        
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Keamanan Data</h2>
        <div class="bg-green-50 p-6 rounded-lg mb-6">
            <h3 class="text-lg font-semibold text-green-800 mb-3">Langkah Keamanan:</h3>
            <ul class="text-green-700 space-y-2">
                <li>✓ Enkripsi SSL untuk semua transaksi</li>
                <li>✓ Server aman dengan firewall berlapis</li>
                <li>✓ Akses terbatas hanya untuk staff berwenang</li>
                <li>✓ Backup data rutin dan aman</li>
                <li>✓ Monitoring keamanan 24/7</li>
            </ul>
        </div>
        
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Berbagi Data dengan Pihak Ketiga</h2>
        <p class="mb-4">Kami hanya membagikan data Anda dengan pihak ketiga dalam situasi berikut:</p>
        <ul class="list-disc pl-6 mb-6 space-y-2">
            <li><strong>Kurir pengiriman</strong> - Untuk proses pengiriman produk</li>
            <li><strong>Payment gateway</strong> - Untuk memproses pembayaran</li>
            <li><strong>Penyedia layanan IT</strong> - Untuk maintenance sistem</li>
            <li><strong>Otoritas hukum</strong> - Jika diwajibkan oleh hukum</li>
        </ul>
        
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Hak Anda</h2>
        <div class="space-y-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
                <h4 class="font-semibold text-blue-800">Akses Data</h4>
                <p class="text-gray-600">Anda berhak meminta salinan data pribadi yang kami miliki</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500">
                <h4 class="font-semibold text-green-800">Koreksi Data</h4>
                <p class="text-gray-600">Anda dapat memperbarui atau memperbaiki data yang tidak akurat</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-yellow-500">
                <h4 class="font-semibold text-yellow-800">Hapus Data</h4>
                <p class="text-gray-600">Anda dapat meminta penghapusan data pribadi Anda</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-red-500">
                <h4 class="font-semibold text-red-800">Portabilitas Data</h4>
                <p class="text-gray-600">Anda dapat meminta transfer data ke penyedia layanan lain</p>
            </div>
        </div>
        
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Cookies dan Teknologi Pelacakan</h2>
        <p class="mb-4">Kami menggunakan cookies untuk:</p>
        <ul class="list-disc pl-6 mb-6 space-y-2">
            <li>Menyimpan preferensi dan pengaturan Anda</li>
            <li>Menganalisis traffic dan penggunaan website</li>
            <li>Menyediakan fitur media sosial</li>
            <li>Menampilkan iklan yang relevan</li>
        </ul>
        
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Retensi Data</h2>
        <div class="bg-gray-50 p-6 rounded-lg mb-6">
            <ul class="space-y-2">
                <li><strong>Data akun:</strong> Selama akun aktif + 2 tahun setelah tidak aktif</li>
                <li><strong>Data transaksi:</strong> 7 tahun untuk keperluan pajak dan audit</li>
                <li><strong>Data marketing:</strong> Hingga Anda unsubscribe atau 3 tahun</li>
                <li><strong>Log sistem:</strong> 1 tahun untuk keperluan keamanan</li>
            </ul>
        </div>
        
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Kontak Privasi</h2>
        <div class="bg-blue-50 p-6 rounded-lg mb-6">
            <p class="text-blue-800 mb-2">Jika Anda memiliki pertanyaan tentang kebijakan privasi ini, hubungi kami:</p>
            <ul class="text-blue-700 space-y-1">
                <li><strong>Email:</strong> privacy@tokosepatu.com</li>
                <li><strong>WhatsApp:</strong> +62 123 4567 890</li>
                <li><strong>Alamat:</strong> Jl. Sepatu No. 123, Jakarta Selatan</li>
            </ul>
        </div>
        
        <div class="bg-yellow-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-yellow-800 mb-2">Perubahan Kebijakan</h3>
            <p class="text-yellow-700">Kami dapat memperbarui kebijakan privasi ini dari waktu ke waktu. Perubahan akan dinotifikasi melalui email atau pengumuman di website.</p>
        </div>
    </div>
</div>
@endsection