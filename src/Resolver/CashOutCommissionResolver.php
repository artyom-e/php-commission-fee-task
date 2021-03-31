<?php

declare(strict_types=1);

namespace App\CommissionTask\Resolver;

use App\CommissionTask\Config\CommissionRule;
use App\CommissionTask\Exception\User\UserTypeNotSupportedException;
use App\CommissionTask\Model\UserType;
use App\CommissionTask\Service\Currency;

class CashOutCommissionResolver extends AbstractCommissionResolver
{
    /**
     * Rules for calculating commission for legal user transactions.
     *
     * @var array
     */
    private $legalRules;

    /**
     * Rules for calculating commission for natural user transactions.
     *
     * @var array
     */
    private $naturalRules;

    /**
     * @return string
     *
     * @throws UserTypeNotSupportedException
     */
    public function resolve(): string
    {
        switch ($this->transaction->getUser()->getType()->getName()) {
            case UserType::LEGAL:
                return $this->resolveLegal();
            case UserType::NATURAL:
                return $this->resolveNatural();
        }

        throw new UserTypeNotSupportedException($this->transaction->getUser()->getType()->getName());
    }

    /**
     * @return string
     *
     * @throws \App\CommissionTask\Exception\Currency\CurrencyConversionNotSupportedException
     */
    private function resolveLegal(): string
    {
        $commission = $this->math->mul($this->transaction->getAmount(), $this->math->div($this->legalRules['percentage'], '100'));
        $commissionInBaseCurrency = Currency::convert($commission, $this->transaction->getCurrency(), $this->legalRules['min_commission']['currency']);
        if ($this->math->comp($commissionInBaseCurrency, $this->legalRules['min_commission']['value']) === -1) {
            $commission = Currency::convert($this->legalRules['min_commission']['value'], $this->legalRules['min_commission']['currency'], $this->transaction->getCurrency());
        }

        return Currency::round($commission, $this->transaction->getCurrency());
    }

    /**
     * @return string
     */
    private function resolveNatural(): string
    {
        //@todo implements method
        return '0.0';
    }

    protected function initRules()
    {
        $rules = CommissionRule::getCashOutRules();
        $this->legalRules = $rules['legal'];
        $this->naturalRules = $rules['natural'];
    }
}
