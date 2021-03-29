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
            'add 2 natural numbers' => ['1', '2', '3'],
            'add negative number to a positive' => ['-1', '2', '1'],
            'add natural number to a float' => ['1', '1.05123', '2.05'],
        ];
    }
    
    public function divProvider(): array
    {
        return [
            'div 2 natural numbers' => ['1', '2', '0.5'],
            'div natural number by a float' => ['1', '1.1497', '0.86'],
            'div float' => ['2.7', '1.1497', '2.34'],
        ];
    }
    
    public function mulProvider(): array
    {
        return [
            'mul 2 natural numbers' => ['1', '2', '2'],
            'mul natural number by a float' => ['2', '1.1497', '2.29'],
            'mul float' => ['2.7', '1.1497', '3.10'],
        ];
    }
    
    public function compProvider(): array
    {
        return [
            'comp 2 natural numbers first lt second' => ['1', '2', -1],
            'comp 2 natural numbers first gt second' => ['2', '1', 1],
            'comp 2 natural equal numbers' => ['2', '2', 0],
            'comp 2 float equal numbers' => ['2.5', '2.5', 0],
            'comp natural with float first lt second' => ['2', '2.5', 0],
            'comp natural with float first gt second' => ['2.5', '2', 0],
        ];
    }
}
