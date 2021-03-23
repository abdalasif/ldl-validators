<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Validators\Config\InterfaceComplianceValidatorConfig;
use LDL\Validators\InterfaceComplianceValidator;
use LDL\Validators\Exception\ValidatorException;
use LDL\Validators\RegexValidator;
use LDL\Validators\ValidatorInterface;
use LDL\Validators\Config\RegexValidatorConfig;

echo "Create interface compliance validator from config\n";
echo "Set config interface: 'ValidatorInterface'\n";

$config = InterfaceComplianceValidatorConfig::fromArray([
    'interface' => ValidatorInterface::class
]);

$validator = InterfaceComplianceValidator::fromConfig($config);

echo "Validate string, exception must be thrown\n";

try{
    $validator->validate('string');
}catch(ValidatorException $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Validate regex config, exception must be thrown\n";

try{
    $validator->validate(new RegexValidatorConfig('#[0-9]+#'));
}catch(ValidatorException $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Validate regex validator\n";

$validator->validate(new RegexValidator('#[0-9]+#'));