<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Validators\Chain\Dumper\ValidatorChainHumanDumper;
use LDL\Validators\RegexValidator;
use LDL\Validators\Chain\AndValidatorChain;
use LDL\Validators\StringValidator;
use LDL\Validators\Chain\Item\ValidatorChainItem;
use LDL\Validators\Chain\Dumper\ValidatorChainExprDumper;

echo "Create AndValidatorChain\n";
echo "Append StringValidator && RegexValidator(#[a-z]+#)\n";

$chain = new AndValidatorChain([
    new StringValidator(),
    new RegexValidator('#[a-z]+#')
]);

dump(ValidatorChainExprDumper::dump($chain));
dump(ValidatorChainHumanDumper::dump($chain));

echo "\nCreate NEGATED AndValidatorChain\n";
echo "Append StringValidator (NOT dumpable) && RegexValidator(#[a-z]+#) (NOT dumpable)\n";

$nChain = new AndValidatorChain([
    new ValidatorChainItem(new StringValidator(), false),
    new ValidatorChainItem(new RegexValidator('#[a-z]+#'), false)
], null, true);

dump(ValidatorChainExprDumper::dump($nChain));
dump(ValidatorChainHumanDumper::dump($nChain));

echo "Remove StringValidator\n";
$nChain->getChainItems()->removeByKey(0);

echo "Append StringValidator negated\n";
$nChain->getChainItems()->append(new StringValidator(true));

dump(ValidatorChainExprDumper::dump($nChain));
dump(ValidatorChainHumanDumper::dump($nChain));
