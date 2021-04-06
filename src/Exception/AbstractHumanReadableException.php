<?php

declare(strict_types=1);

namespace App\CommissionTask\Exception;

use App\CommissionTask\Cli\Log;
use Exception;

abstract class AbstractHumanReadableException extends Exception
{
    abstract public function getLogLevel(): string;

    /**
     * Display exception message.
     */
    public function printExceptionMessage()
    {
        (new Log())->raw($this->getMessage(), $this->getLogLevel());
    }
}
