<?php

declare(strict_types=1);

namespace App\CommissionTask\Service\CurrencyConvertor;

use App\CommissionTask\Config\CurrencyConversionRate;

class UsdToEurConvertor extends AbstractConvertor
{
    /**
     * {@inheritdoc}
     */
    public function convert(string $amount): string
    {
        return $this->math->div($amount, CurrencyConversionRate::getUsdInEur());
    }
}
