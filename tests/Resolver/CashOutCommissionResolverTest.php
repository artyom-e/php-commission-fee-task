<?php

namespace App\CommissionTask\Tests\Resolver;

use App\CommissionTask\Collection\TransactionByUserCollection;
use App\CommissionTask\Exception\Currency\CurrencyConversionNotSupportedException;
use App\CommissionTask\Exception\User\UserTypeNotSupportedException;
use App\CommissionTask\Model\Transaction;
use App\CommissionTask\Model\TransactionType;
use App\CommissionTask\Model\User;
use App\CommissionTask\Model\UserType;
use App\CommissionTask\Resolver\CashOutCommissionResolver;
use App\CommissionTask\Service\Currency;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class CashOutCommissionResolverTest extends TestCase
{
    /**
     * @param Transaction                 $transaction
     * @param TransactionByUserCollection $userTransactions
     * @param string                      $expectation
     *
     * @throws CurrencyConversionNotSupportedException
     * @throws UserTypeNotSupportedException
     * @dataProvider cashOutForLegalProvider
     */
    public function testCashOutForLegal(
        Transaction $transaction,
        TransactionByUserCollection $userTransactions,
        string $expectation
    ) {
        $resolver = new CashOutCommissionResolver($transaction, $userTransactions);
        $this->assertEquals($expectation, $resolver->resolve());
    }
    
    public function cashOutForLegalProvider()
    {
        $user = new User(1, new UserType('legal'));
        $transactionType = new TransactionType('cash_out');
        
        return [
            [
                new Transaction($user, Currency::USD_CODE, '50.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '0.58'
            ],
            [
                new Transaction($user, Currency::USD_CODE, '5000.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '15.00'
            ],
            [
                new Transaction($user, Currency::USD_CODE, '16667.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '50.01'
            ],
            [
                new Transaction($user, Currency::USD_CODE, '40000.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '120.00'
            ],
            [
                new Transaction($user, Currency::USD_CODE, '30000.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '90.00'
            ],
            [
                new Transaction($user, Currency::EUR_CODE, '5.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '0.50'
            ],
            [
                new Transaction($user, Currency::EUR_CODE, '17000.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '51.00'
            ],
        ];
    }
}