<?php

declare(strict_types=1);

namespace App\CommissionTask\Cli;

class Log
{
    /**
     * Print red message.
     */
    public function error(string $message)
    {
        $this->print("\033[31m{$message}\033[0m");
    }

    public function info(string $message)
    {
        $this->print($message);
    }

    /**
     * Print yellow message.
     */
    public function warning(string $message)
    {
        $this->print("\033[33m{$message}\033[0m");
    }

    private function print(string $message)
    {
        echo $message.PHP_EOL;
    }
}
