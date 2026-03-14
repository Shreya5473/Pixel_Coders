<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Webkul\Sales\Contracts\Order;

class AdminNewOrderAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Order Placed #'.$this->order->increment_id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.admin-alert',
        );
    }
}
