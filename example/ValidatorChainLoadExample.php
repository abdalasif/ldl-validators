<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use LDL\Validators\RegexValidator;
use LDL\Validators\StringValidator;
use LDL\Validators\IntegerValidator;
use LDL\Validators\Chain\OrValidatorChain;
use LDL\Validators\Chain\AndValidatorChain;
use LDL\Validators\NumericComparisonValidator;
use LDL\Framework\Base\Exception\RuntimeException;
use LDL\Validators\Chain\Dumper\ValidatorChainPhpDumper;
use LDL\Validators\Chain\Dumper\ValidatorChainJsonDumper;
use LDL\Validators\Chain\Loader\ValidatorChainJsonLoader;

echo "Create validator chain in json\n";

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

$json = ValidatorChainJsonDumper::dump($chain);
dump($json) . "\n";

echo "\nRecreate chain from json(sleep for 3 seconds):\n";

$loadedChain = ValidatorChainJsonLoader::fromJsonString($json);
dump(ValidatorChainPhpDumper::dump($loadedChain)) . "\n";

try {
    echo "\nCheck if the loaded chain doesn't match with the original(EXCEPTION will be thrown on mismatch)\n";
    if ($json !== ValidatorChainJsonDumper::dump($loadedChain)) {
        throw new RuntimeException("Chains differ!");
    }

    echo "Chains matched!\n";
} catch (Exception $e) {
    echo "Exception {$e->getMessage()}\n";
}
