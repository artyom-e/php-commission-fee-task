<?php

declare(strict_types=1);

namespace App\CommissionTask\Model;

use Carbon\Carbon;

class Transaction
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $amount;

    /**
     * @var Carbon
     */
    private $date;

    /**
     * @var TransactionType
     */
    private $type;

    public function __construct(User $user, string $currency, string $amount, TransactionType $type, Carbon $date)
    {
        $this->user = $user;
        $this->currency = $currency;
        $this->amount = $amount;
        $this->type = $type;
        $this->date = $date;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getDate(): Carbon
    {
        return $this->date;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getType(): TransactionType
    {
        return $this->type;
    }
}
