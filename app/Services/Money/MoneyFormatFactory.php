<?php

declare(strict_types=1);

namespace App\Services\Money;

use App\Contracts\MoneyFormatInterface;
use InvalidArgumentException;

class MoneyFormatFactory
{
    /**
     * Create a money formatter for the given currency code.
     *
     * @throws InvalidArgumentException
     */
    public static function create(string $currencyCode = 'GBP'): MoneyFormatInterface
    {
        return match (strtoupper($currencyCode)) {
            'GBP' => new GBPMoneyFormatter,
            // ... can add others in due course
            default => throw new InvalidArgumentException("Unsupported currency: {$currencyCode}"),
        };
    }
}
