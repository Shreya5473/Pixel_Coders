<?php

namespace App\Listeners;

use App\Mail\AdminNewOrderAlert;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Webkul\Sales\Contracts\Order as OrderContract;

class SendOrderNotifications
{
    /**
     * Send customer and admin notifications after order placement.
     */
    public function handle(OrderContract $order): void
    {
        if (! empty($order->customer_email)) {
            Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));
        }

        $adminAddress = config('mail.admin.address', env('ADMIN_MAIL_ADDRESS'));

        if (! empty($adminAddress)) {
            Mail::to($adminAddress)->send(new AdminNewOrderAlert($order));
        }
    }
}
