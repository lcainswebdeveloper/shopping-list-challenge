<?php

declare(strict_types=1);

namespace App\Services\Money;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;

abstract class MoneyFormatter
{
    protected readonly IntlMoneyFormatter $formatter;
    protected readonly Currency $currency;

    protected readonly string $currencyCode;
    protected readonly string $symbol;

    public function __construct()
    {
        $config = $this->getConfig();
        if (empty($config['code'])) {
            throw new \InvalidArgumentException('Currency code must not be empty');
        }
        $this->currencyCode = $config['code'];
        $this->symbol = $config['symbol'];
        $this->currency = new Currency($config['code']);

        $currencies = new ISOCurrencies;
        $numberFormatter = new \NumberFormatter($config['locale'], \NumberFormatter::CURRENCY);
        $this->formatter = new IntlMoneyFormatter($numberFormatter, $currencies);
    }

    public function format(int $amountInMinorUnits): string
    {
        $money = new Money($amountInMinorUnits, $this->currency);

        return $this->formatter->format($money);
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @return array<string, string>
     */
    abstract protected function getConfig(): array;
}
