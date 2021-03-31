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
            'legal' => [
                'min_commission' => [
                    'value' => '0.50',
                    'currency' => Currency::EUR_CODE,
                ],
                'percentage' => '0.3',
            ],

            //@todo implement init natural user rules
            'natural' => [],
        ];
    }
}
