<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pembayaran Berhasil</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4CAF50; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .order-details { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .footer { text-align: center; padding: 20px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pembayaran Berhasil!</h1>
        </div>
        
        <div class="content">
            <p>Halo <strong>{{ $order->user->name ?? 'Customer' }}</strong>,</p>
            
            <p>Terima kasih! Pembayaran Anda telah berhasil diproses.</p>
            
            <div class="order-details">
                <h3>Detail Pesanan:</h3>
                <p><strong>Nomor Order:</strong> #{{ $order->order_number }}</p>
                <p><strong>Total Pembayaran:</strong> Rp {{ number_format($order->total_amount) }}</p>
                <p><strong>Status:</strong> Pembayaran Berhasil</p>
                <p><strong>Tanggal:</strong> {{ $order->updated_at->format('d M Y H:i') }}</p>
            </div>
            
            <p>Pesanan Anda sedang diproses dan akan segera dikirim. Kami akan mengirimkan informasi pengiriman melalui email ini.</p>
            
            <p>Terima kasih telah berbelanja di {{ config('app.name') }}!</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>