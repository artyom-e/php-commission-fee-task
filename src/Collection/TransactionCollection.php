<?php

declare(strict_types=1);

namespace App\CommissionTask\Collection;

use App\CommissionTask\Model\Transaction;
use App\CommissionTask\Model\User;

class TransactionCollection
{
    /**
     * @var TransactionByUserCollection[]
     */
    private $transactions;

    /**
     * @param Transaction[] $transactions
     */
    public function __construct($transactions = [])
    {
        $this->transactions = [];
        foreach ($transactions as $transaction) {
            $this->push($transaction);
        }
    }

    /**
     * Get all user transactions.
     */
    public function allByUser(User $user): TransactionByUserCollection
    {
        return $this->transactions[$user->getId()] ?? new TransactionByUserCollection();
    }

    /**
     * Push transaction to collection.
     */
    public function push(Transaction $transaction): TransactionCollection
    {
        if (!isset($this->transactions[$transaction->getUser()->getId()])) {
            $this->transactions[$transaction->getUser()->getId()] = new TransactionByUserCollection();
        }
        $this->transactions[$transaction->getUser()->getId()]->push($transaction);

        return $this;
    }
}
