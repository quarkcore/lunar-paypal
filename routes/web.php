<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Lancodev\LunarPaypal\Http\Controllers\OrdersController;
use Lancodev\LunarPaypal\PaypalPaymentType;
use Lunar\Models\Cart;
use Lunar\Models\Transaction;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

Route::prefix('lunar-paypal')->group(function () {
    Route::post('/orders', [OrdersController::class, 'create'])->name('lunar-paypal.orders.create');

    Route::post('/orders/{order_id}/capture', [OrdersController::class, 'capture'])->name('lunar-paypal.orders.capture');
});
