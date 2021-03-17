<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Validators\Exception\NumericRangeValidatorException;
use LDL\Validators\NumericRangeValidator;
use LDL\Validators\Config\NumericRangeValidatorConfig;

echo "Create numeric range validator from config: min=3 | max=7\n";

$validator = NumericRangeValidator::fromConfig(NumericRangeValidatorConfig::fromArray([
    'min' => 3,
    'max' => 7
]));

echo "Validate number 4\n";

$validator->validate(4);

echo "Validate number 5\n";

$validator->validate(5);

echo "Validate number greater than 7, exception must be thrown\n";

try{
    $validator->validate(8);
}catch(NumericRangeValidatorException $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Validate number less than 3, exception must be thrown\n";

try{
    $validator->validate(2);
}catch(NumericRangeValidatorException $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}