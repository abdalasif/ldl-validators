<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Validators\Chain\AndValidatorChain;
use LDL\Validators\IntegerValidator;
use LDL\Validators\NumericComparisonValidator;
use LDL\Framework\Helper\ComparisonOperatorHelper;
use LDL\Validators\Chain\Dumper\ValidatorChainExprDumper;
use LDL\Validators\Chain\Dumper\ValidatorChainHumanDumper;

echo "Create AndValidatorChain\n";

echo "Append IntegerValidator'\n";
echo "Append AndValidatorChain with two NumericComparisonValidator'\n";
echo "Minimum: 100 | Maximum: 599\n";

$chain = new AndValidatorChain([
    new IntegerValidator(false, true,'Is an integer'),
    new AndValidatorChain([
        new NumericComparisonValidator(100, ComparisonOperatorHelper::OPERATOR_GTE,false,true,'Number is greater or equal than 100'),
        new NumericComparisonValidator(599, ComparisonOperatorHelper::OPERATOR_LTE, false, true,'Number is lower or equal than 599'),
    ])
],
    false,
    true);

dump(ValidatorChainExprDumper::dump($chain));
dump(ValidatorChainHumanDumper::dump($chain));

echo "Validate: 99, exception must be thrown\n";

try{
    $chain->validate(99);
}catch(\Exception $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Validate: 600, exception must be thrown\n";

try{
    $chain->validate(600);
}catch(\Exception $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Validate: 100\n";
$chain->validate(100);
echo "OK!\n";