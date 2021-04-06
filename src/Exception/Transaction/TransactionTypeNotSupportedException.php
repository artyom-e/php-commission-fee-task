<?php

declare(strict_types=1);

namespace App\CommissionTask\Exception\Transaction;

use App\CommissionTask\Cli\Log;
use App\CommissionTask\Exception\AbstractHumanReadableException;
use Throwable;

class TransactionTypeNotSupportedException extends AbstractHumanReadableException
{
    public function __construct(string $userType, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf("Transaction type '%s' is not supported.", $userType), $code, $previous);
    }

    public function getLogLevel(): string
    {
        return Log::ERROR_LEVEL;
    }
}
