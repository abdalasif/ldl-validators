<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Validators\ClassComplianceValidator;
use LDL\Validators\Collection\ValidatorCollection;
use LDL\Validators\StringEqualsValidator;
use LDL\Validators\Exception\ValidatorException;
use LDL\Validators\RegexValidator;

echo "Create class compliance validator from config\n";
echo "Set config class: 'RegexValidator'\n";

$validator = ClassComplianceValidator::fromConfig([
    'class' => RegexValidator::class
]);

echo "Validate integer, exception must be thrown\n";

try{
    $validator->validate(1);
}catch(ValidatorException $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Validate validator collection, exception must be thrown\n";

try{
    $validator->validate(new ValidatorCollection());
}catch(ValidatorException $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Validate Exact string match validator, exception must be thrown\n";

try{
    $validator->validate(new StringEqualsValidator('test'));
}catch(ValidatorException $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Validate regex validator\n";
$validator->validate(new RegexValidator('#[0-9]+#'));
echo "OK!\n";