<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\MoneyFormatInterface;
use App\Services\Money\GBPMoneyFormatter;
use Illuminate\Support\ServiceProvider;

class MoneyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(MoneyFormatInterface::class, function () {
            return new GBPMoneyFormatter;
        });
    }
}
