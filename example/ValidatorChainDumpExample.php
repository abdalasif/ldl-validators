<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use LDL\Validators\RegexValidator;
use LDL\Validators\StringValidator;
use LDL\Validators\IntegerValidator;
use LDL\Validators\Chain\OrValidatorChain;
use LDL\Validators\Chain\AndValidatorChain;
use LDL\Validators\NumericComparisonValidator;
use LDL\Validators\Chain\Dumper\ValidatorChainPhpDumper;
use LDL\Validators\Chain\Dumper\ValidatorChainExprDumper;
use LDL\Validators\Chain\Dumper\ValidatorChainJsonDumper;

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
]);

echo "\nDump chain as boolean expression:\n";
echo ValidatorChainExprDumper::dump($chain);

echo "\n\nDump chain as PHP (sleep for 3 seconds):\n";
sleep(3);
dump(ValidatorChainPhpDumper::dump($chain));


echo "\nDump chain as JSON (sleep for 3 seconds):\n";
sleep(3);
dump(ValidatorChainJsonDumper::dump($chain));
