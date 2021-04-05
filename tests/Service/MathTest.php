<?php

declare(strict_types=1);

namespace App\CommissionTask\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\CommissionTask\Service\Math;

class MathTest extends TestCase
{
    /**
     * @var Math
     */
    private $math;

    public function setUp()
    {
        $this->math = new Math(2);
    }
    
    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider addProvider
     */
    public function testAdd(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->add($leftOperand, $rightOperand)
        );
    }
    
    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider subProvider
     */
    public function testSub(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->sub($leftOperand, $rightOperand)
        );
    }
    
    
    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider divProvider
     */
    public function testDiv(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->div($leftOperand, $rightOperand)
        );
    }
    
    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider mulProvider
     */
    public function testMul(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->mul($leftOperand, $rightOperand)
        );
    }
    
    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider compProvider
     */
    public function testComp(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->comp($leftOperand, $rightOperand)
        );
    }

    public function addProvider(): array
    {
        return [
            ['1', '2', '3'],
            ['-1', '2', '1'],
            ['1', '1.05123', '2.05'],
        ];
    }
    
    public function subProvider(): array
    {
        return [
            ['1', '2', '-1'],
            ['-1', '2', '-3'],
            ['-1', '-2', '1'],
            ['1.7', '1', '0.7'],
            ['1.7', '1.55', '0.15'],
            ['1.7', '-1.55', '3.25'],
            ['-1.7', '1.55', '-3.25'],
        ];
    }
    
    public function divProvider(): array
    {
        return [
           ['1', '2', '0.5'],
           ['1', '1.1497', '0.86'],
           ['2.7', '1.1497', '2.34'],
        ];
    }
    
    public function mulProvider(): array
    {
        return [
           ['1', '2', '2'],
           ['2', '1.1497', '2.29'],
           ['2.7', '1.1497', '3.10'],
        ];
    }
    
    public function compProvider(): array
    {
        return [
           ['1', '2', -1],
           ['2', '1', 1],
           ['2', '2', 0],
           ['2.5', '2.5', 0],
           ['2', '2.5', -1],
           ['2.5', '2', 1],
        ];
    }
}
