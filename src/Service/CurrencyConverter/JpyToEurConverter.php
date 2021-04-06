<?php

declare(strict_types=1);

namespace App\CommissionTask\Service\CurrencyConverter;

use App\CommissionTask\Config\CurrencyConversionRate;

class JpyToEurConverter extends AbstractConverter
{
    /**
     * {@inheritdoc}
     */
    public function convert(string $amount): string
    {
        return $this->math->div($amount, CurrencyConversionRate::getJpyInEur());
    }
}
