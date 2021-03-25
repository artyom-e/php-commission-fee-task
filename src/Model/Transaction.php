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

    /**
     * @var string
     */
    private $type;

    public function __construct(User $user, string $currency, float $amount, string $type, Carbon $date)
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

    public function getAmount(): float
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

    public function getType(): string
    {
        return $this->type;
    }
}
