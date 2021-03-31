<?php

declare(strict_types=1);

namespace App\CommissionTask\Collection;

use App\CommissionTask\Model\Transaction;
use App\CommissionTask\Model\User;

class TransactionCollection
{
    /**
     * @var array
     */
    private $transactions;

    /**
     * TransactionCollection constructor.
     *
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
     * Get all user transactions
     * @param User $user
     *
     * @return array
     */
    public function allByUser(User $user): array
    {
        return $this->transactions[$user->getId()] ?? [];
    }
    
    /**
     * Get all user transactions with specific transaction type
     *
     * @param User   $user
     * @param string $type transaction type
     *
     * @return array
     */
    public function allByUserTransactionType(User $user, string $type): array
    {
        $transactions = $this->transactions[$user->getId()] ?? [];

        return array_filter($transactions, function (Transaction $transaction) use ($type) {
            return $transaction->getType()->getName() === $type;
        });
    }

    public function push(Transaction $transaction): TransactionCollection
    {
        $this->transactions[$transaction->getUser()->getId()][] = $transaction;

        return $this;
    }
}
