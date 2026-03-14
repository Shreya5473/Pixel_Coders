<?php

return [
    'cashondelivery' => [
        'code' => 'cashondelivery',
        'title' => 'Cash On Delivery',
        'description' => 'Cash On Delivery',
        'class' => 'Webkul\Payment\Payment\CashOnDelivery',
        'active' => true,
        'sort' => 1,
    ],

    'moneytransfer' => [
        'code' => 'moneytransfer',
        'title' => 'Money Transfer',
        'description' => 'Money Transfer',
        'class' => 'Webkul\Payment\Payment\MoneyTransfer',
        'active' => true,
        'sort' => 2,
    ],

    'stripe' => [
        'code' => 'stripe',
        'title' => 'Stripe',
        'description' => 'Pay securely via Stripe',
        'class' => 'Webkul\Payment\Payment\Stripe',
        'active' => true,
        'sort' => 3,
    ],
];
