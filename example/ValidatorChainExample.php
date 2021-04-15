<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Validators\Chain\Loader\ValidatorChainLoader;
use LDL\Validators\Chain\Dumper\ValidatorChainDumper;
use LDL\Validators\ClassComplianceValidator;
use LDL\Validators\HasValidatorConfigInterface;
use LDL\Validators\RegexValidator;
use LDL\Validators\Chain\ValidatorChain;
use LDL\Validators\InterfaceComplianceValidator;
use LDL\Validators\Chain\ValidatorChainInterface;

echo "Create Validator Chain\n";

$chain = new ValidatorChain([]);

echo "Append RegexValidator\n";

$chain->append(new RegexValidator('#[0-9]+#', false));

echo "Append InterfaceComplianceValidator\n";

$chain->append(new InterfaceComplianceValidator(ValidatorChainInterface::class, false));

echo "Append ClassComplianceValidator (NOT dumpable)\n";

$chain->append(new ClassComplianceValidator(ValidatorChain::class, false), null, false);

echo "Validate: 123\n";

$chain->validate('123');

echo "Check succeded validators\n";

$chain->getSuccededValidators()->map(static function($item){
    var_dump(get_class($item));
    return $item;
});

echo "Check error validators\n";

$chain->getErrorValidators()->map(static function($item){
    var_dump(get_class($item));
    return $item;
});

echo "Try to append a class that is not a Validator, exception must be thrown\n";

try{
    $chain->append(new stdClass());
}catch(\Exception $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Filter by class: 'RegexValidator'\n";

$regex = $chain->filterByClass(RegexValidator::class);

echo "Check filtered validators\n";

foreach($regex as $validator){
    echo get_class($validator)."\n";
}

echo "Filter by interface: 'HasValidatorConfigInterface'\n";

$configs = $chain->filterByInterface(HasValidatorConfigInterface::class);

echo "Check filtered validators\n";

foreach($configs as $validator){
    echo get_class($validator)."\n";
}

echo "Dump chain\n";

$file = tempnam(sys_get_temp_dir(),'ldl_config_collection_example');

ValidatorChainDumper::dump($chain, $file);
echo "--------------------------------------\n";

echo "Create Validator Chain from dumped file\n";

$chain = ValidatorChainLoader::load(json_decode(file_get_contents($file), true));

foreach($chain as $validator){
    echo get_class($validator)."\n";
}

unlink($file);