<?php

namespace App\CommissionTask\Tests\Resolver;

use App\CommissionTask\Collection\TransactionByUserCollection;
use App\CommissionTask\Exception\Currency\CurrencyConversionNotSupportedException;
use App\CommissionTask\Model\Transaction;
use App\CommissionTask\Model\TransactionType;
use App\CommissionTask\Model\User;
use App\CommissionTask\Model\UserType;
use App\CommissionTask\Resolver\CashInCommissionResolver;
use App\CommissionTask\Service\Currency;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class CashInCommissionResolverTest extends TestCase
{
    /**
     * @param Transaction $transaction
     * @param TransactionByUserCollection $userTransactions
     * @param string $expectation
     *
     * @throws CurrencyConversionNotSupportedException
     * @dataProvider cashInProvider
     */
    public function testCashIn(
        Transaction $transaction,
        TransactionByUserCollection $userTransactions,
        string $expectation
    ) {
        $resolver = new CashInCommissionResolver($transaction, $userTransactions);
        $this->assertEquals($expectation, $resolver->resolve());
    }
    
    public function cashInProvider()
    {
        $legalUser = new User(1, new UserType('legal'));
        $naturalUser = new User(2, new UserType('natural'));
        $transactionType = new TransactionType('cash_in');
        
        return [
            [
                new Transaction($naturalUser, Currency::USD_CODE, '50.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '0.02'
            ],
            [
                new Transaction($naturalUser, Currency::USD_CODE, '5000.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '1.50'
            ],
            [
                new Transaction($naturalUser, Currency::USD_CODE, '16667.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '5.01'
            ],
            [
                new Transaction($naturalUser, Currency::USD_CODE, '40000.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '5.75'
            ],
            [
                new Transaction($naturalUser, Currency::USD_CODE, '30000.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '5.75'
            ],
            [
                new Transaction($naturalUser, Currency::EUR_CODE, '17000.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '5.00'
            ],
            
            [
                new Transaction($legalUser, Currency::USD_CODE, '50.00', $transactionType, Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '0.02'
            ],
            [
                new Transaction($legalUser, Currency::USD_CODE, '5000.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '1.50'
            ],
            [
                new Transaction($legalUser, Currency::USD_CODE, '16667.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '5.01'
            ],
            [
                new Transaction($legalUser, Currency::USD_CODE, '40000.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '5.75'
            ],
            [
                new Transaction($legalUser, Currency::USD_CODE, '30000.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '5.75'
            ],
            [
                new Transaction($legalUser, Currency::EUR_CODE, '17000.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '5.00'
            ]
        ];
    }
}