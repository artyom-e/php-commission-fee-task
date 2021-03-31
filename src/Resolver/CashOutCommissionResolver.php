<?php

declare(strict_types=1);

namespace App\CommissionTask\Resolver;

use App\CommissionTask\Config\CommissionRule;
use App\CommissionTask\Exception\User\UserTypeNotSupportedException;
use App\CommissionTask\Model\UserType;

class CashOutCommissionResolver extends AbstractCommissionResolver
{
    /**
     * @return string
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

    private function resolveLegal(): string
    {
        //@todo implements method
        return '0.0';
    }

    private function resolveNatural(): string
    {
        //@todo implements method
        return '0.0';
    }

    protected function initRules()
    {
        $rules = CommissionRule::getCashOutRules();
        //@todo implements method
    }
}
