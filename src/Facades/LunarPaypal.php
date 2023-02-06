<?php

namespace Lancodev\LunarPaypal\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Lancodev\LunarPaypal\LunarPaypal
 */
class LunarPaypal extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Lancodev\LunarPaypal\LunarPaypal::class;
    }
}
