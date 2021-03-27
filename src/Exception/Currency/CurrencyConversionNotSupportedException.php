<?php

declare(strict_types=1);

namespace App\CommissionTask\Exception\Currency;

use Throwable;

class CurrencyConversionNotSupportedException extends CurrencyException
{
    public function __construct(string $from, string $to, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf("Conversion between '{$from}-{$to}' is not supported"), $code, $previous);
    }
}
