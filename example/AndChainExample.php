<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Validators\Chain\AndValidatorChain;
use LDL\Validators\IntegerValidator;
use LDL\Validators\NumericComparisonValidator;
use LDL\Framework\Helper\ComparisonOperatorHelper;

echo "Create AndValidatorChain\n";

echo "Append IntegerValidator'\n";
echo "Append AndValidatorChain with two NumericComparisonValidator'\n";
echo "Minimum: 100 | Maximum: 599\n";

$chain = new AndValidatorChain([
    new IntegerValidator(),
    new AndValidatorChain([
        new NumericComparisonValidator(100, ComparisonOperatorHelper::OPERATOR_GTE),
        new NumericComparisonValidator(599, ComparisonOperatorHelper::OPERATOR_LTE),
    ])
]);

dump(\LDL\Validators\Chain\Dumper\ValidatorChainExprDumper::dump($chain));

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