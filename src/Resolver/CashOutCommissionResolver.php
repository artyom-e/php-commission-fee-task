<?php

declare(strict_types=1);

namespace App\CommissionTask\Resolver;

use App\CommissionTask\Config\CommissionRule;

class CashOutCommissionResolver extends AbstractCommissionResolver
{
    public function resolve(): string
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
