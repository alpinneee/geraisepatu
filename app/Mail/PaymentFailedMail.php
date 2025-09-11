<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentFailedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Pembayaran Gagal - Pesanan #' . $this->order->order_number)
                    ->view('emails.payment-failed')
                    ->with([
                        'order' => $this->order,
                        'customerName' => $this->order->user->name ?? json_decode($this->order->shipping_address)->name,
                    ]);
    }
}