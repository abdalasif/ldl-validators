<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Validators\Collection\ValidatorCollection;
use LDL\Validators\InterfaceComplianceValidator;
use LDL\Validators\Exception\ValidatorException;
use LDL\Validators\RegexValidator;
use LDL\Validators\ValidatorInterface;

echo "Create interface compliance validator from config\n";
echo "Set config interface: 'ValidatorInterface'\n";

$validator = InterfaceComplianceValidator::fromConfig([
    'interface' => ValidatorInterface::class
]);

echo "Validate string, exception must be thrown\n";

try{
    $validator->validate('string');
}catch(ValidatorException $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Validate validator collection, exception must be thrown\n";

try{
    $validator->validate(new ValidatorCollection());
}catch(ValidatorException $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Validate regex validator\n";

$validator->validate(new RegexValidator('#[0-9]+#'));
echo "OK!\n";