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
    
    /**
     * @param Transaction                 $transaction
     * @param TransactionByUserCollection $userTransactions
     * @param string                      $expectation
     *
     * @throws CurrencyConversionNotSupportedException
     * @throws UserTypeNotSupportedException
     * @dataProvider cashOutForNaturalProvider
     */
    public function testCashOutForNatural(
        Transaction $transaction,
        TransactionByUserCollection $userTransactions,
        string $expectation
    ) {
        $resolver = new CashOutCommissionResolver($transaction, $userTransactions);
        $this->assertEquals($expectation, $resolver->resolve());
    }
    
    public function cashOutForLegalProvider()
    {
        $user = new User(1, UserType::legal());
        $transactionType = new TransactionType(TransactionType::CASH_OUT);
        
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
    
    
    public function cashOutForNaturalProvider()
    {
        $user = new User(1, UserType::natural());
        $transactionType = new TransactionType(TransactionType::CASH_OUT);
        
        return [
            [
                new Transaction($user, Currency::EUR_CODE, '900.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '0.00'
            ],
            [
                new Transaction($user, Currency::EUR_CODE, '1000.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '0.00'
            ],
            [
                new Transaction($user, Currency::EUR_CODE, '1001.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '0.01'
            ],
            [
                new Transaction($user, Currency::EUR_CODE, '1000.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection([
                    new Transaction($user, Currency::EUR_CODE, '1000.00', $transactionType, Carbon::parse('2020-01-01')),
                ]),
                '3.00'
            ],
            [
                new Transaction($user, Currency::EUR_CODE, '1000.00', $transactionType,
                    Carbon::parse('2020-01-06')),
                new TransactionByUserCollection([
                    new Transaction($user, Currency::EUR_CODE, '1000.00', $transactionType, Carbon::parse('2020-01-01')),
                ]),
                '0.00'
            ],
            [
                new Transaction($user, Currency::EUR_CODE, '10.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection([
                    new Transaction($user, Currency::EUR_CODE, '10.00', $transactionType, Carbon::parse('2019-12-30')),
                    new Transaction($user, Currency::EUR_CODE, '20.00', $transactionType, Carbon::parse('2019-12-31')),
                ]),
                '0.00'
            ],
            [
                new Transaction($user, Currency::EUR_CODE, '10.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection([
                    new Transaction($user, Currency::EUR_CODE, '10.00', $transactionType, Carbon::parse('2019-12-30')),
                    new Transaction($user, Currency::EUR_CODE, '20.00', $transactionType, Carbon::parse('2019-12-31')),
                    new Transaction($user, Currency::EUR_CODE, '30.00', $transactionType, Carbon::parse('2020-01-01')),
                ]),
                '0.03'
            ],
            [
                new Transaction($user, Currency::EUR_CODE, '10.00', $transactionType,
                    Carbon::parse('2020-01-06')),
                new TransactionByUserCollection([
                    new Transaction($user, Currency::EUR_CODE, '10.00', $transactionType, Carbon::parse('2019-12-30')),
                    new Transaction($user, Currency::EUR_CODE, '20.00', $transactionType, Carbon::parse('2019-12-31')),
                    new Transaction($user, Currency::EUR_CODE, '30.00', $transactionType, Carbon::parse('2020-01-01')),
                ]),
                '0.00'
            ],
            
            //USD
            [
                new Transaction($user, Currency::USD_CODE, '1000.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '0.00'
            ],
            [
                new Transaction($user, Currency::USD_CODE, '1149.70', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '0.00'
            ],
            [
                new Transaction($user, Currency::USD_CODE, '1150.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '0.01'
            ],
            [
                new Transaction($user, Currency::USD_CODE, '1149.70', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection([
                    new Transaction($user, Currency::USD_CODE, '1149.70', $transactionType, Carbon::parse('2020-01-01')),
                ]),
                '3.45'
            ],
            [
                new Transaction($user, Currency::USD_CODE, '1149.70', $transactionType,
                    Carbon::parse('2020-01-06')),
                new TransactionByUserCollection([
                    new Transaction($user, Currency::USD_CODE, '1149.70', $transactionType, Carbon::parse('2020-01-01')),
                ]),
                '0.00'
            ],
            [
                new Transaction($user, Currency::USD_CODE, '10.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection([
                    new Transaction($user, Currency::USD_CODE, '10.00', $transactionType, Carbon::parse('2019-12-30')),
                    new Transaction($user, Currency::USD_CODE, '20.00', $transactionType, Carbon::parse('2019-12-31')),
                ]),
                '0.00'
            ],
            [
                new Transaction($user, Currency::USD_CODE, '10.00', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection([
                    new Transaction($user, Currency::USD_CODE, '10.00', $transactionType, Carbon::parse('2019-12-30')),
                    new Transaction($user, Currency::USD_CODE, '20.00', $transactionType, Carbon::parse('2019-12-31')),
                    new Transaction($user, Currency::USD_CODE, '30.00', $transactionType, Carbon::parse('2020-01-01')),
                ]),
                '0.03'
            ],
            [
                new Transaction($user, Currency::USD_CODE, '10.00', $transactionType,
                    Carbon::parse('2020-01-06')),
                new TransactionByUserCollection([
                    new Transaction($user, Currency::USD_CODE, '10.00', $transactionType, Carbon::parse('2019-12-30')),
                    new Transaction($user, Currency::USD_CODE, '20.00', $transactionType, Carbon::parse('2019-12-31')),
                    new Transaction($user, Currency::USD_CODE, '30.00', $transactionType, Carbon::parse('2020-01-01')),
                ]),
                '0.00'
            ],

            //JPY
            [
                new Transaction($user, Currency::JPY_CODE, '120000', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '0'
            ],
            [
                new Transaction($user, Currency::JPY_CODE, '129530', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '0'
            ],
            [
                new Transaction($user, Currency::JPY_CODE, '129630', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection(),
                '1'
            ],
            [
                new Transaction($user, Currency::JPY_CODE, '129530', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection([
                    new Transaction($user, Currency::JPY_CODE, '129530', $transactionType, Carbon::parse('2020-01-01')),
                ]),
                '389'
            ],
            [
                new Transaction($user, Currency::JPY_CODE, '129530', $transactionType,
                    Carbon::parse('2020-01-06')),
                new TransactionByUserCollection([
                    new Transaction($user, Currency::JPY_CODE, '129530', $transactionType, Carbon::parse('2020-01-01')),
                ]),
                '0'
            ],
            [
                new Transaction($user, Currency::JPY_CODE, '300', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection([
                    new Transaction($user, Currency::JPY_CODE, '400', $transactionType, Carbon::parse('2019-12-30')),
                    new Transaction($user, Currency::JPY_CODE, '500', $transactionType, Carbon::parse('2019-12-31')),
                ]),
                '0'
            ],
            [
                new Transaction($user, Currency::JPY_CODE, '3000', $transactionType,
                    Carbon::parse('2020-01-02')),
                new TransactionByUserCollection([
                    new Transaction($user, Currency::JPY_CODE, '400', $transactionType, Carbon::parse('2019-12-30')),
                    new Transaction($user, Currency::JPY_CODE, '500', $transactionType, Carbon::parse('2019-12-31')),
                    new Transaction($user, Currency::JPY_CODE, '600', $transactionType, Carbon::parse('2020-01-01')),
                ]),
                '9'
            ],
            [
                new Transaction($user, Currency::JPY_CODE, '3000', $transactionType,
                    Carbon::parse('2020-01-06')),
                new TransactionByUserCollection([
                    new Transaction($user, Currency::JPY_CODE, '400', $transactionType, Carbon::parse('2019-12-30')),
                    new Transaction($user, Currency::JPY_CODE, '500', $transactionType, Carbon::parse('2019-12-31')),
                    new Transaction($user, Currency::JPY_CODE, '600', $transactionType, Carbon::parse('2020-01-01')),
                ]),
                '0'
            ],

            //different currencies
            [
                new Transaction($user, Currency::USD_CODE, '1138.20', $transactionType,
                    Carbon::parse('2020-01-03')),
                new TransactionByUserCollection([
                    new Transaction($user, Currency::EUR_CODE, '10.00', $transactionType, Carbon::parse('2019-12-30')),
                ]),
                '0.00'
            ],
            [
                new Transaction($user, Currency::USD_CODE, '500', $transactionType,
                    Carbon::parse('2020-01-03')),
                new TransactionByUserCollection([
                    new Transaction($user, Currency::EUR_CODE, '10.00', $transactionType, Carbon::parse('2019-12-30')),
                    new Transaction($user, Currency::JPY_CODE, '1000', $transactionType, Carbon::parse('2019-12-30')),
                ]),
                '0.00'
            ],
        ];
    }
}