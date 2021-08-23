<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Validators\StringContainsValidator;

$jpSTR1 = '思いをする';
$jpSTR2 = 'くろ';
$jpSTR3 = '赤い';

$validator = new StringContainsValidator(
    'い',
    'UTF-8'
);


echo "Validate that Japanese string: $jpSTR1 contains い\n";
$validator->validate($jpSTR1);

try {
    echo "Validate that Japanese string: $jpSTR2 contains い\n";
    $validator->validate($jpSTR2);
}catch(\Exception $e){
    echo "OK: {$e->getMessage()}\n";
}

echo "Validate that Japanese string: $jpSTR3 contains い\n";
$validator->validate($jpSTR3);

echo "\nNEGATED Validation:\n";
echo "####################################################\n\n";

$validator = $validator->getConfig();
$validator['negated'] = true;
$validator = StringContainsValidator::fromConfig($validator);

try {
    echo "Validate that Japanese string: $jpSTR1 does NOT contain い\n";
    $validator->validate($jpSTR1);
}catch(\Exception $e){
    echo "OK {$e->getMessage()}\n";
}

echo "Validate that Japanese string: $jpSTR2 does NOT contain い\n";
$validator->validate($jpSTR2);

try {
    echo "Validate that Japanese string: $jpSTR3 does NOT contain い\n";
    $validator->validate($jpSTR3);
}catch(\Exception $e){
    echo "OK {$e->getMessage()}\n";
}

