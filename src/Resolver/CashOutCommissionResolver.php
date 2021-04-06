<?php

declare(strict_types=1);

namespace App\CommissionTask\Resolver;

use App\CommissionTask\Config\CommissionRule;
use App\CommissionTask\Exception\Currency\CurrencyConversionNotSupportedException;
use App\CommissionTask\Exception\User\UserTypeNotSupportedException;
use App\CommissionTask\Model\TransactionType;
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
     * @throws UserTypeNotSupportedException
     * @throws CurrencyConversionNotSupportedException
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
     * Calculate commission for legal user cash out transaction.
     *
     * @throws CurrencyConversionNotSupportedException
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
     * Calculate commission for natural user cash out transaction.
     *
     * @throws CurrencyConversionNotSupportedException
     */
    private function resolveNatural(): string
    {
        $cashOutTransactions = $this->userTransactions->allByTransactionType(TransactionType::CASH_OUT);
        $transactionDate = $this->transaction->getDate();
        $transactionCount = $cashOutTransactions->countByWeek($transactionDate->weekOfYear, $transactionDate->year);
        $transactionSum = $cashOutTransactions->sumByWeek($transactionDate->weekOfYear, $transactionDate->year, $this->naturalRules['zero_commission_rules']['currency']);
        $transactionAmountInBaseCurrency = Currency::convert($this->transaction->getAmount(), $this->transaction->getCurrency(), $this->naturalRules['zero_commission_rules']['currency']);
        $transactionSumAfterNewTransaction = $this->math->add($transactionSum, $transactionAmountInBaseCurrency);

        $amountForCommission = $this->transaction->getAmount();
        if ($transactionCount < $this->naturalRules['zero_commission_rules']['max_transactions_per_week']) {
            if ($this->math->comp($transactionSum, $this->naturalRules['zero_commission_rules']['max_amount_per_week']) === -1) {
                if ($this->math->comp($transactionSumAfterNewTransaction, $this->naturalRules['zero_commission_rules']['max_amount_per_week']) === 1) {
                    $amount = $this->math->sub($transactionSumAfterNewTransaction, $this->naturalRules['zero_commission_rules']['max_amount_per_week']);
                    $amountForCommission = Currency::convert($amount, $this->naturalRules['zero_commission_rules']['currency'], $this->transaction->getCurrency());
                } else {
                    //transaction sum lte max transaction amount per week
                    $amountForCommission = '0.00';
                }
            }
        }
        $commission = $this->math->mul($amountForCommission, $this->math->div($this->naturalRules['percentage'], '100'));

        return Currency::round($commission, $this->transaction->getCurrency());
    }

    /**
     * {@inheritdoc}
     */
    protected function initRules()
    {
        $rules = CommissionRule::getCashOutRules();
        $this->legalRules = $rules[UserType::LEGAL];
        $this->naturalRules = $rules[UserType::NATURAL];
    }
}
