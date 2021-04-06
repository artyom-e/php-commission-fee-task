<?php

declare(strict_types=1);

namespace App\CommissionTask\Model;

class TransactionType
{
    const CASH_IN = 'cash_in';
    const CASH_OUT = 'cash_out';

    /**
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public static function cashIn(): TransactionType
    {
        return new self(self::CASH_IN);
    }

    public static function cashOut(): TransactionType
    {
        return new self(self::CASH_OUT);
    }
}
