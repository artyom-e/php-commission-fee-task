<?php

declare(strict_types=1);

namespace App\CommissionTask;

use App\CommissionTask\Cli\Log;
use App\CommissionTask\Collection\TransactionCollection;
use App\CommissionTask\Exception\AbstractHumanReadableException;
use App\CommissionTask\Exception\File\FileNotFoundException;
use App\CommissionTask\Exception\Transaction\TransactionTypeNotSupportedException;
use App\CommissionTask\Model\Transaction;
use App\CommissionTask\Model\TransactionType;
use App\CommissionTask\Model\User;
use App\CommissionTask\Model\UserType;
use App\CommissionTask\Resolver\AbstractCommissionResolver;
use App\CommissionTask\Resolver\CashInCommissionResolver;
use App\CommissionTask\Resolver\CashOutCommissionResolver;
use Carbon\Carbon;

class Application
{
    /**
     * @var string
     */
    private $filePath;

    /**
     * @var TransactionCollection
     */
    private $transactionCollection;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->transactionCollection = new TransactionCollection();
    }

    /**
     * @throws AbstractHumanReadableException
     */
    public function run()
    {
        $this->validateFilePath();

        $log = new Log();
        $handle = fopen($this->filePath, 'r');
        while (($row = fgetcsv($handle)) !== false) {
            $userType = new UserType((string) $row[2]);
            $user = new User((int) $row[1], $userType);
            $transactionType = new TransactionType((string) $row[3]);
            $transaction = new Transaction($user, (string) $row[5], (string) $row[4], $transactionType, Carbon::parse($row[0]));
            $resolver = $this->getCommissionResolver($transaction);
            $commission = $resolver->resolve();
            $this->transactionCollection->push($transaction);

            $log->info($commission);
        }
        fclose($handle);
    }

    /**
     * @throws FileNotFoundException
     */
    private function validateFilePath()
    {
        if (!file_exists($this->filePath)) {
            throw new FileNotFoundException($this->filePath);
        }
    }

    /**
     * @throws TransactionTypeNotSupportedException
     */
    private function getCommissionResolver(Transaction $transaction): AbstractCommissionResolver
    {
        switch ($transaction->getType()->getName()) {
            case TransactionType::CASH_IN:
                $resolver = new CashInCommissionResolver($transaction, $this->transactionCollection->allByUser($transaction->getUser()));

                break;
            case TransactionType::CASH_OUT:
                $resolver = new CashOutCommissionResolver($transaction, $this->transactionCollection->allByUser($transaction->getUser()));

                break;
            default:
                throw new TransactionTypeNotSupportedException($transaction->getType()->getName());
        }

        return $resolver;
    }
}
