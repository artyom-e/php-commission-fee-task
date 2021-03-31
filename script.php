<?php

use App\CommissionTask\Cli\ArgumentValidator;
use App\CommissionTask\Cli\Log;
use App\CommissionTask\Collection\TransactionCollection;
use App\CommissionTask\Exception\AbstractHumanReadableException;
use App\CommissionTask\Model\Transaction;
use App\CommissionTask\Model\TransactionType;
use App\CommissionTask\Model\User;
use App\CommissionTask\Model\UserType;
use App\CommissionTask\Resolver\CashInCommissionResolver;
use App\CommissionTask\Resolver\CashOutCommissionResolver;
use Carbon\Carbon;

require 'vendor/autoload.php';

$log = new Log();

try {
    $argumentsValidator = new ArgumentValidator($_SERVER['argv']);
    $arguments = $argumentsValidator->validate();
    $handle = fopen($arguments['path'], 'r');
    $transactionCollection = new TransactionCollection();
    while (($row = fgetcsv($handle)) !== false) {
        $userType = new UserType((string)$row[2]);
        $user = new User((int)$row[1], $userType);
        $transactionType = new TransactionType((string)$row[3]);
        $transaction = new Transaction($user, (string)$row[5], (float)$row[4], $transactionType, Carbon::parse($row[0]));
        switch ($transactionType->getName()) {
            case 'cash_in':
                $resolver = new CashInCommissionResolver($transaction, $transactionCollection->allByUser($user));
                
                break;
            case 'cash_out':
                $resolver = new CashOutCommissionResolver($transaction, $transactionCollection->allByUser($user));
                
                break;
            default:
                $log->warning(sprintf("Transaction type '%s' is not supported.", $transactionType->getName()));
                continue;
        }
        $commission = $resolver->resolve();
        $log->info($commission);
        
        $transactionCollection->push($transaction);
    }
    fclose($handle);
} catch(AbstractHumanReadableException $exception) {
    $exception->printExceptionMessage();
}