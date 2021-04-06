<?php

declare(strict_types=1);

namespace App\CommissionTask\Config;

use App\CommissionTask\Service\Currency;

class CommissionRule
{
    public static function getCashInRules(): array
    {
        return [
            'max_commission' => [
                'value' => '5.00',
                'currency' => Currency::EUR_CODE,
            ],
            'percentage' => '0.03',
        ];
    }

    public static function getCashOutRules(): array
    {
        return [
            UserType::LEGAL => [
                'min_commission' => [
                    'value' => '0.50',
                    'currency' => Currency::EUR_CODE,
                ],
                'percentage' => '0.3',
            ],
            UserType::NATURAL => [
                'percentage' => '0.3',
                'zero_commission_rules' => [
                    'max_amount_per_week' => '1000.00',
                    'currency' => Currency::EUR_CODE,
                    'max_transactions_per_week' => 3,
                ],
            ],
        ];
    }
}
