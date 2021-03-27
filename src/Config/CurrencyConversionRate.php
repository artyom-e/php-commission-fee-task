<?php

declare(strict_types=1);

namespace App\CommissionTask\Config;

class CurrencyConversionRate
{
    public static function getJpyInEur(): string
    {
        return '129.53';
    }

    public static function getUsdInEur(): string
    {
        return '1.1497';
    }
}
