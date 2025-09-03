<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Checkout Berhasil</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2196F3; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .order-details { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .items { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .footer { text-align: center; padding: 20px; color: #666; }
        .btn { display: inline-block; padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛒 Checkout Berhasil!</h1>
        </div>
        
        <div class="content">
            <p>Halo <strong>{{ $order->user->name ?? json_decode($order->shipping_address)->name }}</strong>,</p>
            
            <p>Terima kasih! Pesanan Anda telah berhasil dibuat dan sedang menunggu pembayaran.</p>
            
            <div class="order-details">
                <h3>Detail Pesanan:</h3>
                <p><strong>Nomor Order:</strong> #{{ $order->order_number }}</p>
                <p><strong>Total:</strong> Rp {{ number_format($order->total_amount) }}</p>
                <p><strong>Status:</strong> Menunggu Pembayaran</p>
                <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                <p><strong>Ekspedisi:</strong> {{ $order->shipping_expedition_name }}</p>
            </div>
            
            <div class="items">
                <h3>Item Pesanan:</h3>
                @foreach($order->items as $item)
                <p>• {{ $item->product->name }} ({{ $item->quantity }}x) - Rp {{ number_format($item->total) }}</p>
                @endforeach
            </div>
            
            <p>Silakan lakukan pembayaran untuk melanjutkan proses pesanan Anda.</p>
            
            <p style="text-align: center;">
                <a href="{{ url('/profile/orders') }}" class="btn">Lihat Pesanan</a>
            </p>
            
            <p>Terima kasih telah berbelanja di {{ config('app.name') }}!</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>