<?php

namespace Webkul\Payment\Payment;

use Illuminate\Support\Facades\Storage;

class Stripe extends Payment
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'stripe';

    /**
     * Stripe is processed after order creation from the checkout API.
     */
    public function getRedirectUrl()
    {
        return null;
    }

    /**
     * Payment method image.
     */
    public function getImage()
    {
        $url = $this->getConfigData('image');

        return $url ? Storage::url($url) : null;
    }
}
