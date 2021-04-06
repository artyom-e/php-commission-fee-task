<?php

use App\CommissionTask\Cli\ArgumentValidator;
use App\CommissionTask\Application;
use App\CommissionTask\Exception\AbstractHumanReadableException;

require 'vendor/autoload.php';

try {
    $argumentsValidator = new ArgumentValidator($_SERVER['argv']);
    $arguments = $argumentsValidator->validate();
    
    $application = new Application($arguments['path']);
    $application->run();
} catch(AbstractHumanReadableException $exception) {
    $exception->printExceptionMessage();
}