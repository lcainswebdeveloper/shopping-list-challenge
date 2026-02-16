<?php

declare(strict_types=1);

namespace App\Contracts;

interface MoneyFormatInterface
{
    /**
     * Format an amount in the smallest currency unit (e.g., pence) to a human-readable string.
     */
    public function format(int $amountInMinorUnits): string;

    /**
     * Get the currency code (e.g., 'GBP', 'USD').
     */
    public function getCurrencyCode(): string;

    /**
     * Get the currency symbol (e.g., '£', '$').
     */
    public function getSymbol(): string;

    /**
     * Get the locale eg en_GB
     */
    // public function getLocale(): string;
}
