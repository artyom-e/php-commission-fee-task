<?php

declare(strict_types=1);

namespace App\CommissionTask\Service;

use App\CommissionTask\Exception\Currency\CurrencyConversionNotSupportedException;
use App\CommissionTask\Service\CurrencyConvertor\AbstractConvertor;
use App\CommissionTask\Service\CurrencyConvertor\EurToJpyConvertor;
use App\CommissionTask\Service\CurrencyConvertor\EurToUsdConvertor;
use App\CommissionTask\Service\CurrencyConvertor\JpyToEurConvertor;
use App\CommissionTask\Service\CurrencyConvertor\UsdToEurConvertor;

class Currency
{
    const EUR_CODE = 'EUR';
    const USD_CODE = 'USD';
    const JPY_CODE = 'JPY';

    /**
     * @throws CurrencyConversionNotSupportedException
     */
    public static function convert(string $amount, string $from, string $to): string
    {
        if ($from === $to) {
            return $amount;
        }
        $convertor = static::getConvertor($from, $to);

        return $convertor->convert($amount);
    }

    /**
     * @throws CurrencyConversionNotSupportedException
     */
    private static function getConvertor(string $from, string $to): AbstractConvertor
    {
        if ($to === self::EUR_CODE) {
            switch ($from) {
                case self::USD_CODE:
                    return new UsdToEurConvertor();
                case self::JPY_CODE:
                    return new JpyToEurConvertor();
                default:
                    throw new CurrencyConversionNotSupportedException($from, $to);
            }
        } elseif ($to === self::USD_CODE) {
            switch ($from) {
                case self::EUR_CODE:
                    return new EurToUsdConvertor();
                default:
                    throw new CurrencyConversionNotSupportedException($from, $to);
            }
        } elseif ($to === self::JPY_CODE) {
            switch ($from) {
                case self::EUR_CODE:
                    return new EurToJpyConvertor();
                default:
                    throw new CurrencyConversionNotSupportedException($from, $to);
            }
        }

        throw new CurrencyConversionNotSupportedException($from, $to);
    }
}
