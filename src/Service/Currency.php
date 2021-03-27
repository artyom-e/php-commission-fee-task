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
        if ($to === 'EUR') {
            switch ($from) {
                case 'USD':
                    return new UsdToEurConvertor();
                case 'JPY':
                    return new JpyToEurConvertor();
                default:
                    throw new CurrencyConversionNotSupportedException($from, $to);
            }
        } elseif ($to === 'USD') {
            switch ($from) {
                case 'EUR':
                    return new EurToUsdConvertor();
                default:
                    throw new CurrencyConversionNotSupportedException($from, $to);
            }
        } elseif ($to === 'JPY') {
            switch ($from) {
                case 'EUR':
                    return new EurToJpyConvertor();
                default:
                    throw new CurrencyConversionNotSupportedException($from, $to);
            }
        }

        throw new CurrencyConversionNotSupportedException($from, $to);
    }
}
