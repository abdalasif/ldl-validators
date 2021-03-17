<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Validators\RegexValidator;
use LDL\Validators\Config\RegexValidatorConfig;
use LDL\Validators\Exception\RegexValidatorException;

echo "Create regex validator from config '#[0-9]+#'\n";

$validator = RegexValidator::fromConfig(RegexValidatorConfig::fromArray([
    'regex' => '#[0-9]+#'
]));

echo "Validate string, exception must be thrown\n";

try{
    $validator->validate('string');
}catch(RegexValidatorException $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Validate number: 123\n";

$validator->validate(123);