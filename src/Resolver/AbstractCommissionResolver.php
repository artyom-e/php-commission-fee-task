<?php

declare(strict_types=1);

namespace App\CommissionTask\Resolver;

use App\CommissionTask\Model\Transaction;

abstract class AbstractCommissionResolver
{
    /**
     * @var Transaction
     */
    protected $transaction;

    /**
     * @var Transaction[]
     */
    private $userTransactions;

    /**
     * AbstractCommissionResolver constructor.
     *
     * @param Transaction[] $userTransactions
     */
    public function __construct(Transaction $transaction, array $userTransactions)
    {
        $this->transaction = $transaction;
        $this->userTransactions = $userTransactions;
    }

    /**
     * Calculate transaction commission.
     */
    abstract public function resolve(): float;
}
