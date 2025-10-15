<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .invoice-info { margin-bottom: 30px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f5f5f5; }
        .total { text-align: right; font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>INVOICE</h1>
        <h2>{{ config('app.name', 'KickVerse') }}</h2>
    </div>

    <div class="invoice-info">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%;">
                    <strong>Invoice Number:</strong> #{{ $order->order_number }}<br>
                    <strong>Date:</strong> {{ $order->created_at->format('d M Y') }}<br>
                    <strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}
                </td>
                <td style="width: 50%; text-align: right;">
                    <strong>Customer:</strong> {{ $order->user->name ?? 'Guest' }}<br>
                    <strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}<br>
                    <strong>Phone:</strong> {{ $order->shipping_phone ?? 'N/A' }}
                </td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>{{ $item->product->name ?? 'Product' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>Rp {{ number_format($item->price) }}</td>
                <td>Rp {{ number_format($item->price * $item->quantity) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="total">Subtotal:</td>
                <td class="total">Rp {{ number_format($order->subtotal ?? 0) }}</td>
            </tr>
            <tr>
                <td colspan="3" class="total">Shipping:</td>
                <td class="total">Rp {{ number_format($order->shipping_cost ?? 0) }}</td>
            </tr>
            <tr>
                <td colspan="3" class="total"><strong>Total:</strong></td>
                <td class="total"><strong>Rp {{ number_format($order->total_amount) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>Generated on {{ now()->format('d M Y H:i') }}</p>
    </div>
</body>
</html>