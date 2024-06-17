<?php

namespace App\Services;

class CurrencyService
{
    const RATES = [
        'usd' => [
            'eur' => 0.98,
            'npr' => 133.60,
            'inr' => 83.53
        ]
    ];

    public function convert(float $amount, string $currencyFrom, string $currencyTo)
    {
        $rate = self::RATES[$currencyFrom][$currencyTo];
        return round($amount * $rate, 2);
    }
}
