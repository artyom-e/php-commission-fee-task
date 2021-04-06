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
     * @param string $amount conversion amount in $from currency
     * @param string $from   currency code
     * @param string $to     currency code
     *
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
     * Round amount based on specified currency.
     */
    public static function round(string $amount, string $currency): string
    {
        switch ($currency) {
            case Currency::JPY_CODE:
                $precision = 0;
                break;
            default:
                $precision = 2;
        }
        $math = new Math($precision + 1);
        $pow = (string) pow(10, $precision);

        return number_format((ceil($math->mul($pow, $amount)) + ceil($math->mul($pow, $amount) - ceil($math->mul($pow, $amount)))) / $pow, $precision, '.', '');
    }

    /**
     * Returns converter based on specified currency pair.
     *
     * @param string $from currency code
     * @param string $to   currency code
     *
     * @throws CurrencyConversionNotSupportedException
     */
    private static function getConvertor(string $from, string $to): AbstractConvertor
    {
        if ($to === self::EUR_CODE) {
            return self::getConvertorForEur($from);
        } elseif ($to === self::USD_CODE) {
            return self::getConvertorForUsd($from);
        } elseif ($to === self::JPY_CODE) {
            return self::getConvertorForJpy($from);
        }

        throw new CurrencyConversionNotSupportedException($from, $to);
    }

    /**
     * @param string $from currency code
     *
     * @throws CurrencyConversionNotSupportedException
     */
    private static function getConvertorForEur(string $from): AbstractConvertor
    {
        switch ($from) {
            case self::USD_CODE:
                return new UsdToEurConvertor();
            case self::JPY_CODE:
                return new JpyToEurConvertor();
            default:
                throw new CurrencyConversionNotSupportedException($from, self::EUR_CODE);
        }
    }

    /**
     * @param string $from currency code
     *
     * @throws CurrencyConversionNotSupportedException
     */
    private static function getConvertorForUsd(string $from): AbstractConvertor
    {
        switch ($from) {
            case self::EUR_CODE:
                return new EurToUsdConvertor();
            default:
                throw new CurrencyConversionNotSupportedException($from, self::USD_CODE);
        }
    }

    /**
     * @param string $from currency code
     *
     * @throws CurrencyConversionNotSupportedException
     */
    private static function getConvertorForJpy(string $from): AbstractConvertor
    {
        switch ($from) {
            case self::EUR_CODE:
                return new EurToJpyConvertor();
            default:
                throw new CurrencyConversionNotSupportedException($from, self::JPY_CODE);
        }
    }
}
