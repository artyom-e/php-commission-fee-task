<?php

declare(strict_types=1);

namespace App\CommissionTask\Collection;

use App\CommissionTask\Exception\Collection\DifferentUsersInCollectionException;
use App\CommissionTask\Exception\Currency\CurrencyConversionNotSupportedException;
use App\CommissionTask\Model\Transaction;
use App\CommissionTask\Model\User;
use App\CommissionTask\Service\Currency;
use App\CommissionTask\Service\Math;
use Carbon\Carbon;

class TransactionByUserCollection
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var Transaction[]
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
     * Get all transactions with specific transaction type.
     *
     * @param string $type transaction type
     */
    public function allByTransactionType(string $type): TransactionByUserCollection
    {
        return new TransactionByUserCollection(
            array_filter($this->transactions, function (Transaction $transaction) use ($type) {
                return $transaction->getType()->getName() === $type;
            })
        );
    }

    /**
     * Get sum of all transaction costs in specified currency for specified week.
     *
     * @throws CurrencyConversionNotSupportedException
     */
    public function sumByWeek(int $weekOfYear, int $year, string $currency): string
    {
        $sum = '0.00';
        $math = new Math(10);

        foreach ($this->transactions as $transaction) {
            if ($this->isWeekEqual($transaction->getDate(), $weekOfYear, $year)) {
                $sum = $math->add($sum, Currency::convert($transaction->getAmount(), $transaction->getCurrency(), $currency));
            }
        }

        return $sum;
    }

    /**
     * Get count of all transactions for specified week.
     */
    public function countByWeek(int $weekOfYear, int $year): int
    {
        $count = 0;
        foreach ($this->transactions as $transaction) {
            if ($this->isWeekEqual($transaction->getDate(), $weekOfYear, $year)) {
                ++$count;
            }
        }

        return $count;
    }

    /**
     * ??hecking the coincidence of weeks.
     */
    private function isWeekEqual(Carbon $date, int $weekOfYear, int $year): bool
    {
        return $date->weekOfYear === $weekOfYear && ($date->copy()->startOfWeek()->year === $year || $date->copy()->endOfWeek()->year === $year);
    }

    /**
     * Push transaction to collection.
     *
     * @throws DifferentUsersInCollectionException
     */
    public function push(Transaction $transaction): TransactionByUserCollection
    {
        if (!$this->user) {
            $this->user = $transaction->getUser();
        } elseif ($this->user->getId() !== $transaction->getUser()->getId()) {
            throw new DifferentUsersInCollectionException();
        }
        $this->transactions[] = $transaction;

        return $this;
    }
}
