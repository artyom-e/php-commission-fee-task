<?php

declare(strict_types=1);

namespace App\CommissionTask\Cli;

class Log
{
    const ERROR_LEVEL = 'error';
    const WARNING_LEVEL = 'warning';
    const INFO_LEVEL = 'info';

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

    /**
     * Print message in selected type.
     */
    public function raw(string $message, string $level)
    {
        switch ($level) {
            case self::ERROR_LEVEL:
                $this->error($message);
                break;
            case self::WARNING_LEVEL:
                $this->warning($message);
                break;
            case self::INFO_LEVEL:
                $this->info($message);
                break;
            default:
                $this->print($message);
        }
    }

    private function print(string $message)
    {
        echo $message.PHP_EOL;
    }
}
