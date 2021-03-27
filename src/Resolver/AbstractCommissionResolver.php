<?php

declare(strict_types=1);

namespace App\CommissionTask\Resolver;

use App\CommissionTask\Model\Transaction;
use App\CommissionTask\Service\Math;

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
     * @var Math
     */
    protected $math;

    public function __construct(Transaction $transaction, array $userTransactions)
    {
        $this->transaction = $transaction;
        $this->userTransactions = $userTransactions;
        $this->math = new Math(10);
        $this->initRules();
    }

    /**
     * Calculate transaction commission.
     */
    abstract public function resolve(): string;

    /**
     * Init commission rules.
     */
    abstract protected function initRules();
}
