<?php

declare(strict_types=1);

namespace App\CommissionTask\Config;

class CommissionRule
{
    public static function getCashInRules(): array
    {
        return [
            'max_commission' => [
                'value' => '5.00',
                'currency' => 'EUR',
            ],
            'percentage' => '0.03',
        ];
    }

    public static function getCashOutRules(): array
    {
        //@todo implements method
        return [];
    }
}
