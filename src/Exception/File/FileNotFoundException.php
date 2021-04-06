<?php

declare(strict_types=1);

namespace App\CommissionTask\Exception\File;

use App\CommissionTask\Cli\Log;
use App\CommissionTask\Exception\AbstractHumanReadableException;
use Throwable;

class FileNotFoundException extends AbstractHumanReadableException
{
    public function __construct(string $path, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf('File "%s" could not be found.', $path), $code, $previous);
    }

    public function getLogLevel(): string
    {
        return Log::ERROR_LEVEL;
    }
}
