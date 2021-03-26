<?php

declare(strict_types=1);

namespace App\CommissionTask\Exception\File;

use Throwable;

class FileNotFoundException extends FileException
{
    public function __construct(string $path, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf('File "%s" could not be found.', $path), $code, $previous);
    }
}
