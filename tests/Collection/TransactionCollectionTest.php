<?php

namespace App\CommissionTask\Tests\Collection;

use App\CommissionTask\Collection\TransactionByUserCollection;
use App\CommissionTask\Collection\TransactionCollection;
use App\CommissionTask\Model\Transaction;
use App\CommissionTask\Model\TransactionType;
use App\CommissionTask\Model\User;
use App\CommissionTask\Model\UserType;
use App\CommissionTask\Service\Currency;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class TransactionCollectionTest extends TestCase
{
    public function testPush()
    {
        $collection = new TransactionCollection();
        $userType = UserType::legal();
        $user = new User(1, $userType);
        
        $this->assertEquals(new TransactionByUserCollection(), $collection->allByUser($user));
        
        $transactionType = TransactionType::cashIn();
        $transaction = new Transaction($user, Currency::EUR_CODE, '5.00', $transactionType, Carbon::now());
        $collection->push($transaction);
    
        $this->assertEquals(new TransactionByUserCollection([$transaction]), $collection->allByUser($user));
    }
    
    public function testAllByUser()
    {
        $collection = new TransactionCollection();
        $userType = UserType::legal();
        $user1 = new User(1, $userType);
        $user2 = new User(2, $userType);
        
        $this->assertEquals(new TransactionByUserCollection(), $collection->allByUser($user1));
        $this->assertEquals(new TransactionByUserCollection(), $collection->allByUser($user2));
        
        $transactionType = TransactionType::cashIn();
        $transaction1 = new Transaction($user1, Currency::EUR_CODE, '5.00', $transactionType, Carbon::now());
        $collection->push($transaction1);
        
        $this->assertEquals(new TransactionByUserCollection([$transaction1]), $collection->allByUser($user1));
        $this->assertEquals(new TransactionByUserCollection(), $collection->allByUser($user2));
        
        $transaction2 = new Transaction($user2, Currency::EUR_CODE, '10.00', $transactionType, Carbon::now());
        $collection->push($transaction2);
        
        $this->assertEquals(new TransactionByUserCollection([$transaction1]), $collection->allByUser($user1));
        $this->assertEquals(new TransactionByUserCollection([$transaction2]), $collection->allByUser($user2));
    }
}