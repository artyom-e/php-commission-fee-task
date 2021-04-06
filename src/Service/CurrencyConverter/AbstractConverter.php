<?php

declare(strict_types=1);

namespace App\CommissionTask\Service\CurrencyConverter;

use App\CommissionTask\Service\Math;

abstract class AbstractConverter
{
    protected $math;

    public function __construct()
    {
        $this->math = new Math(10);
    }

    /**
     * Convert currency.
     */
    abstract public function convert(string $amount): string;
}
