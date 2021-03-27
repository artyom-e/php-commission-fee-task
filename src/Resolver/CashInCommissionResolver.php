<?php

declare(strict_types=1);

namespace App\CommissionTask\Resolver;

use App\CommissionTask\Config\CommissionRule;
use App\CommissionTask\Exception\Currency\CurrencyConversionNotSupportedException;
use App\CommissionTask\Service\Currency;

class CashInCommissionResolver extends AbstractCommissionResolver
{
    /**
     * Maximum Commission Fee in Base Currency.
     *
     * @var string
     */
    private $maxFee;

    /**
     * Maximum Commission Fee Currency.
     *
     * @var string
     */
    private $maxFeeCurrency;

    /**
     * Commission Percentage.
     *
     * @var string
     */
    private $commissionPercentage;

    /**
     * @throws CurrencyConversionNotSupportedException
     */
    public function resolve(): string
    {
        $commission = $this->math->mul($this->transaction->getAmount(), $this->math->div($this->commissionPercentage, '100'));
        $commissionInBaseCurrency = Currency::convert($commission, $this->transaction->getCurrency(), $this->maxFeeCurrency);
        if ($this->math->comp($commissionInBaseCurrency, $this->maxFee) === 1) {
            $commission = Currency::convert($this->maxFee, $this->maxFeeCurrency, $this->transaction->getCurrency());
        }
        //@todo add rounding response

        return $commission;
    }

    protected function initRules()
    {
        $rules = CommissionRule::getCashInRules();
        $this->maxFee = $rules['max_commission']['value'];
        $this->maxFeeCurrency = $rules['max_commission']['currency'];
        $this->commissionPercentage = $rules['percentage'];
    }
}
