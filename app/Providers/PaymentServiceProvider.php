<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Midtrans\Config;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Config::$serverKey = config('midtrans.serverKey');
        Config::$clientKey = config('midtrans.clientKey');
        Config::$isProduction = (bool) config('midtrans.isProduction');
        Config::$isSanitized = (bool) config('midtrans.isSanitized', true);
        Config::$is3ds = (bool) config('midtrans.is3ds', true);
    }
}