<?php

declare(strict_types=1);

namespace App\CommissionTask\Service;

use App\CommissionTask\Exception\Currency\CurrencyConversionNotSupportedException;
use App\CommissionTask\Service\CurrencyConverter\AbstractConverter;
use App\CommissionTask\Service\CurrencyConverter\EurToJpyConverter;
use App\CommissionTask\Service\CurrencyConverter\EurToUsdConverter;
use App\CommissionTask\Service\CurrencyConverter\JpyToEurConverter;
use App\CommissionTask\Service\CurrencyConverter\UsdToEurConverter;

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
        $converter = static::getConverter($from, $to);

        return $converter->convert($amount);
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
    private static function getConverter(string $from, string $to): AbstractConverter
    {
        if ($to === self::EUR_CODE) {
            return self::getConverterForEur($from);
        } elseif ($to === self::USD_CODE) {
            return self::getConverterForUsd($from);
        } elseif ($to === self::JPY_CODE) {
            return self::getConverterForJpy($from);
        }

        throw new CurrencyConversionNotSupportedException($from, $to);
    }

    /**
     * @param string $from currency code
     *
     * @throws CurrencyConversionNotSupportedException
     */
    private static function getConverterForEur(string $from): AbstractConverter
    {
        switch ($from) {
            case self::USD_CODE:
                return new UsdToEurConverter();
            case self::JPY_CODE:
                return new JpyToEurConverter();
            default:
                throw new CurrencyConversionNotSupportedException($from, self::EUR_CODE);
        }
    }

    /**
     * @param string $from currency code
     *
     * @throws CurrencyConversionNotSupportedException
     */
    private static function getConverterForUsd(string $from): AbstractConverter
    {
        switch ($from) {
            case self::EUR_CODE:
                return new EurToUsdConverter();
            default:
                throw new CurrencyConversionNotSupportedException($from, self::USD_CODE);
        }
    }

    /**
     * @param string $from currency code
     *
     * @throws CurrencyConversionNotSupportedException
     */
    private static function getConverterForJpy(string $from): AbstractConverter
    {
        switch ($from) {
            case self::EUR_CODE:
                return new EurToJpyConverter();
            default:
                throw new CurrencyConversionNotSupportedException($from, self::JPY_CODE);
        }
    }
}
