<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Validators\RegexValidator;
use LDL\Validators\Config\RegexValidatorConfig;
use LDL\Validators\Exception\ValidatorException;

echo "Create regex validator from config\n";
echo "Set config regex: '#[0-9]+#'\n";

$config = RegexValidatorConfig::fromArray([
    'regex' => '#[0-9]+#'
]);

$validator = RegexValidator::fromConfig($config);

echo "Validate string, exception must be thrown\n";

try{
    $validator->validate('string');
}catch(ValidatorException $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Validate number: 123\n";

$validator->validate(123);