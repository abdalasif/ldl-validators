<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';


use LDL\Validators\Chain\Dumper\ValidatorChainJsonDumper;
use LDL\Validators\Chain\Dumper\ValidatorChainPhpDumper;
use LDL\Validators\NumericComparisonValidator;
use LDL\Validators\RegexValidator;
use LDL\Validators\Chain\AndValidatorChain;
use LDL\Validators\Chain\OrValidatorChain;
use LDL\Validators\IntegerValidator;
use LDL\Validators\Chain\Exception\CombinedException;
use LDL\Validators\StringValidator;
use LDL\Validators\Chain\Dumper\ValidatorChainExprDumper;

echo "Create Validator Chain\n";

$chain = new OrValidatorChain([
    new AndValidatorChain([
        new StringValidator(),
        new RegexValidator('#[a-z]+#')
    ]),
    new AndValidatorChain([
        new IntegerValidator(),
        new OrValidatorChain([
            new IntegerValidator(),
            new AndValidatorChain([
                new NumericComparisonValidator(500, '>'),
                new NumericComparisonValidator(10, '<='),
            ])
        ])
    ]),
    new OrValidatorChain([
        new RegexValidator('#[a-z]+#')
    ])
],false);

echo "Validate: 'abc'\n";
$chain->validate('abc');
echo "OK!\n";

echo "Validate: 123\n";
$chain->validate(123);
echo "OK!\n";

echo "Validate: '@@@'\n";
try{
    $chain->validate('@@@');
}catch(CombinedException $e){
    dump("EXCEPTION: {$e->getCombinedMessage()}");
}

echo "\nDump chain as boolean expression:\n";
echo ValidatorChainExprDumper::dump($chain);

echo "\nDump chain as PHP:\n";
sleep(3);
dump(ValidatorChainPhpDumper::dump($chain));


echo "\nDump chain as JSON:\n";
sleep(3);
echo ValidatorChainJsonDumper::dump($chain)."\n\n";

//\LDL\Validators\Chain\Loader\ValidatorChainJsonLoader::load(ValidatorChainJsonDumper::dump($chain));