<?php

declare(strict_types=1);

namespace App\CommissionTask\Tests\Collection;

use App\CommissionTask\Collection\TransactionByUserCollection;
use App\CommissionTask\Exception\Collection\DifferentUsersInCollectionException;
use App\CommissionTask\Model\Transaction;
use App\CommissionTask\Model\TransactionType;
use App\CommissionTask\Model\User;
use App\CommissionTask\Model\UserType;
use App\CommissionTask\Service\Currency;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class TransactionByUserCollectionTest extends TestCase
{
    public function testPush()
    {
        $collection = new TransactionByUserCollection();
        $userType = UserType::legal();
        $user = new User(1, $userType);
        
        $transactionType = TransactionType::cashIn();
        $this->assertEquals(new TransactionByUserCollection(), $collection->allByTransactionType($transactionType->getName()));
        
        $transaction = new Transaction($user, Currency::EUR_CODE, '5.00', $transactionType, Carbon::now());
        $collection->push($transaction);
    
        $this->assertEquals(new TransactionByUserCollection([$transaction]), $collection->allByTransactionType($transactionType->getName()));
    }
    
    public function testPushDifferentUsers()
    {
        $this->expectException(DifferentUsersInCollectionException::class);
        
        $collection = new TransactionByUserCollection();
        $userType = UserType::legal();
        $user1 = new User(1, $userType);
        $user2 = new User(2, $userType);
        
        $transactionType = TransactionType::cashIn();
        
        $transaction1 = new Transaction($user1, Currency::EUR_CODE, '5.00', $transactionType, Carbon::now());
        $collection->push($transaction1);
        $transaction2 = new Transaction($user2, Currency::EUR_CODE, '5.00', $transactionType, Carbon::now());
        $collection->push($transaction2);
    }
    
    public function testAllByTransactionType()
    {
        $collection = new TransactionByUserCollection();
        $userType = UserType::legal();
        $user = new User(1, $userType);
        
        $cashInTransactionType = TransactionType::cashIn();
        $cashInTransaction = new Transaction($user, Currency::EUR_CODE, '5.00', $cashInTransactionType, Carbon::now());
        $collection->push($cashInTransaction);
        
        $cashOutTransactionType = TransactionType::cashOut();
        $cashOutTransaction = new Transaction($user, Currency::EUR_CODE, '6.00', $cashOutTransactionType, Carbon::now());
        $collection->push($cashOutTransaction);
        
        $this->assertEquals(new TransactionByUserCollection([$cashInTransaction]), $collection->allByTransactionType($cashInTransactionType->getName()));
        
        $this->assertEquals(new TransactionByUserCollection([$cashOutTransaction]), $collection->allByTransactionType($cashOutTransactionType->getName()));
    }
    
    /**
     * @dataProvider countByWeekProvider
     *
     * @param TransactionByUserCollection $collection
     * @param int                         $weekOfYear
     * @param int                         $year
     * @param int                         $expectation
     */
    public function testCountByWeek(TransactionByUserCollection $collection, int $weekOfYear, int $year, int $expectation)
    {
        $this->assertEquals($expectation, $collection->countByWeek($weekOfYear, $year));
    }
    
    /**
     * @dataProvider sumByWeekProvider
     *
     * @param TransactionByUserCollection $collection
     * @param int                         $weekOfYear
     * @param int                         $year
     * @param string                      $currency
     * @param string                      $expectation
     */
    public function testSumByWeek(TransactionByUserCollection $collection, int $weekOfYear, int $year, string $currency, string $expectation)
    {
        $this->assertEquals($expectation, $collection->sumByWeek($weekOfYear, $year, $currency));
    }
    
    public function countByWeekProvider()
    {
        $userType = UserType::legal();
        $user = new User(1, $userType);
        $transactionType = TransactionType::cashIn();
        
        return [
            [
                new TransactionByUserCollection([
                    new Transaction($user, Currency::EUR_CODE, '5.00', $transactionType, Carbon::parse('2021-03-29')),
                    new Transaction($user, Currency::EUR_CODE, '6.00', $transactionType, Carbon::parse('2021-03-30')),
                ]),
                13,
                2021,
                2
            ],
            [
                new TransactionByUserCollection([
                    new Transaction($user, Currency::EUR_CODE, '4.00', $transactionType, Carbon::parse('2021-03-28')),
                    new Transaction($user, Currency::EUR_CODE, '5.00', $transactionType, Carbon::parse('2021-03-29')),
                    new Transaction($user, Currency::EUR_CODE, '6.00', $transactionType, Carbon::parse('2021-03-30')),
                ]),
                13,
                2021,
                2
            ],
            [
                new TransactionByUserCollection([
                    new Transaction($user, Currency::EUR_CODE, '4.00', $transactionType, Carbon::parse('2020-12-28')),
                    new Transaction($user, Currency::EUR_CODE, '5.00', $transactionType, Carbon::parse('2021-01-01')),
                    new Transaction($user, Currency::EUR_CODE, '6.00', $transactionType, Carbon::parse('2021-01-02')),
                ]),
                53,
                2020,
                3
            ],
        ];
    }
    
    public function sumByWeekProvider()
    {
        $userType = UserType::legal();
        $user = new User(1, $userType);
        $transactionType = TransactionType::cashIn();
        
        return [
            [
                new TransactionByUserCollection([
                    new Transaction($user, Currency::EUR_CODE, '5.00', $transactionType, Carbon::parse('2021-03-29')),
                    new Transaction($user, Currency::EUR_CODE, '6.00', $transactionType, Carbon::parse('2021-03-30')),
                ]),
                13,
                2021,
                Currency::EUR_CODE,
                '11.00'
            ],
            [
                new TransactionByUserCollection([
                    new Transaction($user, Currency::EUR_CODE, '4.00', $transactionType, Carbon::parse('2021-03-28')),
                    new Transaction($user, Currency::EUR_CODE, '5.00', $transactionType, Carbon::parse('2021-03-29')),
                    new Transaction($user, Currency::EUR_CODE, '6.00', $transactionType, Carbon::parse('2021-03-30')),
                ]),
                13,
                2021,
                Currency::EUR_CODE,
                '11.00'
            ],
            [
                new TransactionByUserCollection([
                    new Transaction($user, Currency::EUR_CODE, '4.00', $transactionType, Carbon::parse('2020-12-28')),
                    new Transaction($user, Currency::EUR_CODE, '5.00', $transactionType, Carbon::parse('2021-01-01')),
                    new Transaction($user, Currency::EUR_CODE, '6.00', $transactionType, Carbon::parse('2021-01-02')),
                ]),
                52,
                2020,
                Currency::EUR_CODE,
                '0.00'
            ],
            [
                new TransactionByUserCollection([
                    new Transaction($user, Currency::EUR_CODE, '4.00', $transactionType, Carbon::parse('2020-12-28')),
                    new Transaction($user, Currency::EUR_CODE, '5.00', $transactionType, Carbon::parse('2021-01-01')),
                    new Transaction($user, Currency::EUR_CODE, '6.00', $transactionType, Carbon::parse('2021-01-02')),
                ]),
                53,
                2020,
                Currency::EUR_CODE,
                '15.00'
            ],
            [
                new TransactionByUserCollection([
                    new Transaction($user, Currency::EUR_CODE, '4.00', $transactionType, Carbon::parse('2020-12-28')),
                    new Transaction($user, Currency::EUR_CODE, '5.00', $transactionType, Carbon::parse('2021-01-01')),
                    new Transaction($user, Currency::EUR_CODE, '6.00', $transactionType, Carbon::parse('2021-01-02')),
                ]),
                53,
                2021,
                Currency::EUR_CODE,
                '15.00'
            ],
            [
                new TransactionByUserCollection([
                    new Transaction($user, Currency::EUR_CODE, '4.00', $transactionType, Carbon::parse('2020-12-28')),
                    new Transaction($user, Currency::EUR_CODE, '5.00', $transactionType, Carbon::parse('2021-01-01')),
                    new Transaction($user, Currency::EUR_CODE, '6.00', $transactionType, Carbon::parse('2021-01-02')),
                ]),
                53,
                2020,
                Currency::USD_CODE,
                '17.2455'
            ],
            [
                new TransactionByUserCollection([
                    new Transaction($user, Currency::EUR_CODE, '4.00', $transactionType, Carbon::parse('2020-12-28')),
                    new Transaction($user, Currency::EUR_CODE, '5.00', $transactionType, Carbon::parse('2021-01-01')),
                    new Transaction($user, Currency::EUR_CODE, '6.00', $transactionType, Carbon::parse('2021-01-02')),
                ]),
                53,
                2021,
                Currency::USD_CODE,
                '17.2455'
            ],
            [
                new TransactionByUserCollection([
                    new Transaction($user, Currency::JPY_CODE, '20000', $transactionType, Carbon::parse('2020-12-28')),
                    new Transaction($user, Currency::JPY_CODE, '30000', $transactionType, Carbon::parse('2021-01-01')),
                    new Transaction($user, Currency::JPY_CODE, '40000', $transactionType, Carbon::parse('2021-01-02')),
                ]),
                53,
                2021,
                Currency::EUR_CODE,
                '694.8197328803'
            ],
        ];
    }
}