<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\CartController;
use Webkul\Shop\Http\Controllers\OnepageController;
use Webkul\Shop\Http\Controllers\StripePaymentController;

/**
 * Cart routes.
 */
Route::controller(CartController::class)->prefix('checkout/cart')->group(function () {
    Route::get('', 'index')->name('shop.checkout.cart.index');
});

Route::controller(OnepageController::class)->prefix('checkout/onepage')->group(function () {
    Route::get('', 'index')->name('shop.checkout.onepage.index');

    Route::get('success', 'success')->name('shop.checkout.onepage.success');
});

Route::controller(StripePaymentController::class)->group(function () {
    Route::get('checkout/stripe/{order}', 'checkout')->name('shop.stripe.checkout');

    Route::post('create-payment-intent', 'createPaymentIntent')->name('shop.stripe.payment.intent');

    Route::get('payment-success', 'paymentSuccess')->name('shop.stripe.payment.success');
});
