<?php

use App\CommissionTask\Cli\ArgumentValidator;
use App\CommissionTask\Cli\Log;
use App\CommissionTask\Exception\Cli\CliException;
use App\CommissionTask\Exception\File\FileException;
use App\CommissionTask\Model\Transaction;
use App\CommissionTask\Model\TransactionType;
use App\CommissionTask\Model\User;
use App\CommissionTask\Model\UserType;
use Carbon\Carbon;

require 'vendor/autoload.php';

$log = new Log();

try {
    $argumentsValidator = new ArgumentValidator($_SERVER['argv']);
    $arguments = $argumentsValidator->validate();
    $handle = fopen($arguments['path'], 'r');
    while (($row = fgetcsv($handle)) !== false) {
        $userType = new UserType((string)$row[2]);
        $user = new User((int)$row[1], $userType);
        $transactionType = new TransactionType((string)$row[3]);
        $transaction = new Transaction($user, (string)$row[5], (float)$row[4], $transactionType, Carbon::parse($row[0]));
        
        //@todo add calculating commission
    }
    fclose($handle);
} catch(CliException $exception) {
    $log->error($exception->getMessage());
} catch(FileException $exception) {
    $log->error($exception->getMessage());
}
