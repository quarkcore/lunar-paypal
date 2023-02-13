<?php

use Illuminate\Support\Facades\Route;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

Route::prefix('lunar-paypal')->group(function () {
    Route::post('/orders', function () {
        logger('creating order');
        $paypal = new PayPalClient();
        $paypal->getAccessToken();
        $paypal->getClientToken();

        $data = json_decode('{
            "intent": "CAPTURE",
            "purchase_units": [
              {
                "amount": {
                  "currency_code": "USD",
                  "value": "100.00"
                }
              }
            ]
        }', true, 512, JSON_THROW_ON_ERROR);

        $order = $paypal->createOrder($data);

        ray($order);

        return $order;
    })->name('lunar-paypal.orders.create');

    Route::post('/orders/{order_id}/capture', function ($orderId) {
        logger('attempting to capture payment for order');
        logger($orderId);
        $paypal = new PayPalClient();
        $paypal->getAccessToken();
        $paypal->getClientToken();

        $order = $paypal->capturePaymentOrder($orderId);

        return $order;
    })->name('lunar-paypal.orders.capture');
});
