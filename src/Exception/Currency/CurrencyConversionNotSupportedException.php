<?php

declare(strict_types=1);

namespace App\CommissionTask\Exception\Currency;

use App\CommissionTask\Cli\Log;
use App\CommissionTask\Exception\AbstractHumanReadableException;
use Throwable;

class CurrencyConversionNotSupportedException extends AbstractHumanReadableException
{
    public function __construct(string $from, string $to, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf("Conversion between '{$from}-{$to}' is not supported."), $code, $previous);
    }

    public function getLogLevel(): string
    {
        return Log::ERROR_LEVEL;
    }
}
