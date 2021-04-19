<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';


use LDL\Validators\RegexValidator;
use LDL\Validators\Chain\AndValidatorChain;
use LDL\Validators\Chain\OrValidatorChain;
use LDL\Validators\IntegerValidator;
use LDL\Validators\Chain\Exception\CombinedException;
use LDL\Validators\StringValidator;
use LDL\Validators\MaxNumericValidator;
use LDL\Validators\MinNumericValidator;
use LDL\Validators\Chain\Dumper\ValidatorChainPhpDumper;
use LDL\Validators\Chain\Dumper\ValidatorChainJsonDumper;
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
                new MaxNumericValidator(500),
                new MinNumericValidator(10),
            ])
        ])
    ]),
    new OrValidatorChain([
        new RegexValidator('#[a-z]+#')
    ])
],true);

echo "Validate: abc\n";

$chain->validate('@abc');

echo "Validate: 123\n";
$chain->validate(123);

try{
    $chain->validate('@@@');
}catch(CombinedException $e){
    dump("EXCEPTION: {$e->getCombinedMessage()}");
}

echo "Dump chain as boolean expression:\n\n";
echo ValidatorChainExprDumper::dump($chain);

echo "Dump chain as PHP:\n\n";\
sleep(5);
dump(ValidatorChainPhpDumper::dump($chain));


echo "Dump chain as JSON:\n\n";
sleep(5);
echo ValidatorChainJsonDumper::dump($chain)."\n\n";

\LDL\Validators\Chain\Loader\ValidatorChainJsonLoader::load(ValidatorChainJsonDumper::dump($chain));