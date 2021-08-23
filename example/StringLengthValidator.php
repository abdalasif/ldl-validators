<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Validators\StringLengthValidator;
use LDL\Framework\Helper\ComparisonOperatorHelper;

$enSTR = 'hello';
$jpSTR = '思いをする';
$krSTR = '안녕하세요';
$esSTR = 'hola';

$length = 5;

$validator = new StringLengthValidator(
    $length,
    ComparisonOperatorHelper::OPERATOR_SEQ,
    'UTF-8'
);

echo "Validate that Japanese string length: $jpSTR is equal to $length characters\n";
$validator->validate($jpSTR);

echo "Validate that English string length: $enSTR is equal to $length characters\n";
$validator->validate($enSTR);

echo "Validate that Korean string length: $krSTR is equal to $length characters\n";
$validator->validate($krSTR);

try {
    echo "Validate that spanish string length: $esSTR is equal to $length characters\n";
    $validator->validate($esSTR);
}catch(\Exception $e){
    echo "OK: {$e->getMessage()}\n";
}

echo "\nNEGATED Validation\n";
echo "######################################################\n\n";

$validator = $validator->getConfig();
$validator['negated'] = true;
$validator = StringLengthValidator::fromConfig($validator);

try {
    echo "Validate that Japanese string length: $jpSTR is NOT equal to $length characters\n";
    $validator->validate($jpSTR);
}catch(\Exception $e){
    echo "OK: {$e->getMessage()}\n";
}

try {
    echo "Validate that English string: $enSTR is NOT equal to $length characters\n";
    $validator->validate($enSTR);
}catch(\Exception $e){
    echo "OK: {$e->getMessage()}\n";
}

try {
    echo "Validate that Korean string length: $krSTR is NOT equal to $length characters\n";
    $validator->validate($krSTR);
}catch(\Exception $e){
    echo "OK: {$e->getMessage()}\n";
}

echo "Validate that Spanish string length: $esSTR is NOT equal to $length characters\n";
$validator->validate($esSTR);
