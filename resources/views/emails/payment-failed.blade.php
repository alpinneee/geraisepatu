<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pembayaran Gagal</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc3545; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8f9fa; }
        .order-info { background: white; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .button { display: inline-block; padding: 12px 24px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pembayaran Gagal</h1>
        </div>
        
        <div class="content">
            <p>Halo {{ $customerName }},</p>
            
            <p>Kami informasikan bahwa pembayaran untuk pesanan Anda <strong>tidak berhasil diproses</strong>.</p>
            
            <div class="order-info">
                <h3>Detail Pesanan:</h3>
                <p><strong>Nomor Pesanan:</strong> {{ $order->order_number }}</p>
                <p><strong>Total Pembayaran:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                <p><strong>Status:</strong> {{ $order->payment_status_label }}</p>
                <p><strong>Tanggal Pesanan:</strong> {{ $order->created_at->format('d F Y H:i') }}</p>
            </div>
            
            <p>Anda dapat mencoba melakukan pembayaran ulang dengan mengklik tombol di bawah ini:</p>
            
            <a href="{{ route('order.continue-payment', $order) }}" class="button">
                Bayar Sekarang
            </a>
            
            <p>Jika Anda mengalami kesulitan atau memiliki pertanyaan, silakan hubungi customer service kami.</p>
            
            <p>Terima kasih atas kepercayaan Anda berbelanja di toko kami.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>