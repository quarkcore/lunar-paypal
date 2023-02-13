<?php

namespace Lancodev\LunarPaypal\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Lancodev\LunarPaypal\PaypalPaymentType
 */
class LunarPaypal extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Lancodev\LunarPaypal\PaypalPaymentType::class;
    }
}
