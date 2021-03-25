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
     * @var float
     */
    private $amount;

    /**
     * @var Carbon
     */
    private $date;

    public function __construct(User $user, string $currency, float $amount, Carbon $date)
    {
        $this->currency = $currency;
        $this->amount = $amount;
        $this->date = $date;
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
