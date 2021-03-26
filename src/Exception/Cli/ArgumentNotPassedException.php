<?php

declare(strict_types=1);

namespace App\CommissionTask\Exception\Cli;

use Throwable;

class ArgumentNotPassedException extends CliException
{
    public function __construct(string $argumentName, $code = 0, Throwable $previous = null)
    {
        parent::__construct($argumentName.' is required.', $code, $previous);
    }
}
