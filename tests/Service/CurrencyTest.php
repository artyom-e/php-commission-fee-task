<?php

declare(strict_types=1);

namespace App\CommissionTask\Tests\Service;

use App\CommissionTask\Config\CurrencyConversionRate;
use App\CommissionTask\Service\Math;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    
    /**
     * @var Math
     */
    private $math;
    
    protected function setUp()
    {
        parent::setUp();
        $this->math = new Math(10);
    }
    
    /**
     * @param string $amount
     * @param string $expectation
     *
     * @dataProvider convertEurToJpyProvider
     */
    public function testConvertEurToJpy(string $amount, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->mul($amount, CurrencyConversionRate::getJpyInEur())
        );
    }
    
    /**
     * @param string $amount
     * @param string $expectation
     *
     * @dataProvider convertEurToUsdProvider
     */
    public function testConvertEurToUsd(string $amount, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->mul($amount, CurrencyConversionRate::getUsdInEur())
        );
    }
    
    /**
     * @param string $amount
     * @param string $expectation
     *
     * @dataProvider convertJpyToEurProvider
     */
    public function testConvertJpyToEur(string $amount, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->div($amount, CurrencyConversionRate::getJpyInEur())
        );
    }
    
    /**
     * @param string $amount
     * @param string $expectation
     *
     * @dataProvider convertUsdToEurProvider
     */
    public function testConvertUsdToEur(string $amount, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->div($amount, CurrencyConversionRate::getUsdInEur())
        );
    }
    
    public function convertEurToJpyProvider()
    {
        return [
            ['0', '0'],
            ['5', '647.65'],
            ['-5', '-647.65'],
            ['5.59', '724.0727'],
        ];
    }
    
    public function convertEurToUsdProvider()
    {
        return [
            ['0', '0'],
            ['5', '5.7485'],
            ['-5', '-5.7485'],
            ['5.59', '6.426823'],
        ];
    }
    
    public function convertJpyToEurProvider()
    {
        return [
            ['0', '0'],
            ['5', '0.0386010962'],
            ['-5', '-0.0386010962'],
            ['5.59', '0.0431560256'],
        ];
    }
    
    public function convertUsdToEurProvider()
    {
        return [
            ['0', '0'],
            ['5', '4.3489605984'],
            ['-5', '-4.3489605984'],
            ['5.59', '4.8621379490'],
        ];
    }
}
