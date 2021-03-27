<?php

declare(strict_types=1);

namespace App\CommissionTask\Service\CurrencyConvertor;

use App\CommissionTask\Service\Math;

abstract class AbstractConvertor
{
    protected $math;

    public function __construct()
    {
        $this->math = new Math(10);
    }

    abstract public function convert(string $amount): string;
}
