<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Validators\Chain\AndValidatorChain;
use LDL\Validators\IntegerValidator;
use LDL\Validators\NumericComparisonValidator;
use LDL\Framework\Helper\ComparisonOperatorHelper;

echo "Create Validator Chain\n";

$chain = new AndValidatorChain([
    new IntegerValidator(),
    new AndValidatorChain([
        new NumericComparisonValidator(100, ComparisonOperatorHelper::OPERATOR_GTE),
        new NumericComparisonValidator(599, ComparisonOperatorHelper::OPERATOR_LTE),
    ])
]);

dump(\LDL\Validators\Chain\Dumper\ValidatorChainExprDumper::dump($chain));

try{
    $chain->validate(200);
}catch(\Exception $e){
    dd($e->getMessage());
}