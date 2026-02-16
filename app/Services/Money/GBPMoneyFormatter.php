<?php

declare(strict_types=1);

namespace App\Services\Money;

use App\Contracts\MoneyFormatInterface;

class GBPMoneyFormatter extends MoneyFormatter implements MoneyFormatInterface
{
    /**
     * @return array{code: string, locale: string, symbol: string}
     */
    protected function getConfig(): array
    {
        return [
            'code' => 'GBP',
            'locale' => 'en_GB',
            'symbol' => '£',
        ];
    }
}
