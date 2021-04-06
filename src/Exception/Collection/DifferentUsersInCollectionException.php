<?php

declare(strict_types=1);

namespace App\CommissionTask\Exception\Collection;

use App\CommissionTask\Cli\Log;
use App\CommissionTask\Exception\AbstractHumanReadableException;
use Throwable;

class DifferentUsersInCollectionException extends AbstractHumanReadableException
{
    public function __construct(string $message = null, $code = 0, Throwable $previous = null)
    {
        if (!$message) {
            $message = 'Cannot push transaction to collection. Transaction user not equal user in collection.';
        }

        parent::__construct($message, $code, $previous);
    }

    public function getLogLevel(): string
    {
        return Log::ERROR_LEVEL;
    }
}
