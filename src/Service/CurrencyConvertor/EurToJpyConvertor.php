<?php

declare(strict_types=1);

namespace App\CommissionTask\Service\CurrencyConvertor;

use App\CommissionTask\Config\CurrencyConversionRate;

class EurToJpyConvertor extends AbstractConvertor
{
    public function convert(string $amount): string
    {
        return $this->math->mul($amount, CurrencyConversionRate::getJpyInEur());
    }
}
