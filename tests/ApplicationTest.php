<?php

declare(strict_types=1);

namespace App\CommissionTask\Tests;

use App\CommissionTask\Application;
use App\CommissionTask\Exception\File\FileNotFoundException;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    private $fileName = 'phpunit.test.csv';
    
    protected function setUp()
    {
        parent::setUp();
        $data = [
            '2014-12-31,4,natural,cash_out,1200.00,EUR',
            '2015-01-01,4,natural,cash_out,1000.00,EUR',
            '2016-01-05,4,natural,cash_out,1000.00,EUR',
            '2016-01-05,1,natural,cash_in,200.00,EUR',
            '2016-01-06,2,legal,cash_out,300.00,EUR',
            '2016-01-06,1,natural,cash_out,30000,JPY',
            '2016-01-07,1,natural,cash_out,1000.00,EUR',
            '2016-01-07,1,natural,cash_out,100.00,USD',
            '2016-01-10,1,natural,cash_out,100.00,EUR',
            '2016-01-10,2,legal,cash_in,1000000.00,EUR',
            '2016-01-10,3,natural,cash_out,1000.00,EUR',
            '2016-02-15,1,natural,cash_out,300.00,EUR',
            '2016-02-19,5,natural,cash_out,3000000,JPY',
        ];
        file_put_contents($this->fileName, implode(PHP_EOL, $data));
    }
    
    protected function tearDown()
    {
        parent::tearDown();
        unlink($this->fileName);
    }
    
    public function testApplication()
    {
        $application = new Application($this->fileName);
        $application->run();
        $this->assertTrue(true);
        
        $expectation = [
            '0.60',
            '3.00',
            '0.00',
            '0.06',
            '0.90',
            '0',
            '0.70',
            '0.30',
            '0.30',
            '5.00',
            '0.00',
            '0.00',
            '8612',
        ];
        $this->expectOutputString(implode(PHP_EOL, $expectation) . PHP_EOL);
    }
    
    public function testApplicationWithInvalidPathToFile()
    {
        $this->expectException(FileNotFoundException::class);
        (new Application('invalid_path_to_file.csv'))->run();
    }
}