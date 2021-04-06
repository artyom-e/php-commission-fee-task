<?php

declare(strict_types=1);

namespace App\CommissionTask\Exception\Cli;

use App\CommissionTask\Cli\Log;
use App\CommissionTask\Exception\AbstractHumanReadableException;
use Throwable;

class ArgumentNotPassedException extends AbstractHumanReadableException
{
    public function __construct(string $argumentName, $code = 0, Throwable $previous = null)
    {
        parent::__construct($argumentName.' is required.', $code, $previous);
    }

    public function getLogLevel(): string
    {
        return Log::ERROR_LEVEL;
    }
}
