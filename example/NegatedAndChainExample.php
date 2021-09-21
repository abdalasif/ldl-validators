<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Validators\RegexValidator;
use LDL\Validators\Chain\AndValidatorChain;
use LDL\Validators\StringValidator;
use LDL\Validators\Chain\Item\ValidatorChainItem;
use LDL\Validators\Chain\Dumper\ValidatorChainExprDumper;
use LDL\Framework\Helper\ComparisonOperatorHelper;

echo "Create AndValidatorChain\n";
echo "Append StringValidator && RegexValidator(#[a-z]+#)\n";

$chain = new AndValidatorChain([
    new StringValidator(),
    new RegexValidator('#[a-z]+#')
]);

dump(ValidatorChainExprDumper::dump($chain));

echo "Validate: 'a'\n";
$chain->validate('a');
echo "OK!\n";

echo "Validate: '@' (exception must be thrown)\n";

try{
    $chain->validate('@');
}catch(\Exception $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Validate: 0 (exception must be thrown)\n";

try{
    $chain->validate(0);
}catch(\Exception $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "\nCreate NEGATED AndValidatorChain\n";

echo "Append StringValidator && RegexValidator(#[a-z]+#)\n";

$nChain = new AndValidatorChain([
    new StringValidator(),
    new RegexValidator('#[a-z]+#')
], null, true);

/**
 * @var \LDL\Validators\Chain\Item\ValidatorChainItemInterface $v
 */
foreach($nChain->getChainItems() as $k=>$v){
    echo sprintf('%s: %s%s', $k, get_class($v->getValidator()),"\n");
}

echo "Validate: 'a' (exception must be thrown)\n";

try{
    $nChain->validate('a');
}catch(\Exception $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Validate: '@'\n";

$nChain->validate('@');
echo "OK!\n";

echo "Validate integer number: 0\n";

$nChain->validate(0);
echo "OK!\n";

foreach($nChain->getChainItems() as $key => $chainItem){
    echo "\nKey: ". $key. " Class: ". get_class($chainItem->getValidator())."\n";
}

echo "Remove StringValidator\n";
$nChain->getChainItems()->removeByKey(0);

echo "One item (RegexValidator) Must still remain in collection";

foreach($nChain->getChainItems() as $key => $chainItem){
    echo "\nKey: ". $key. " Class: ". get_class($chainItem->getValidator())."\n";
}

if(count($nChain->getChainItems()) !== 1){
    throw new \Exception('Remaining items is not equal to 1!');
}

foreach($nChain->getChainItems() as $key => $chainItem){
    echo "\nKey: ". $key. " Class: ". get_class($chainItem->getValidator())."\n";
}

echo "Append StringValidator negated (NOT dumpable)\n";

$nChain->getChainItems()
    ->append(
        new ValidatorChainItem(
            new StringValidator(true),
            false
        ),
        null
    );

echo "Check items\n";

foreach($nChain->getChainItems() as $key => $chainItem){
    echo "Key: ". $key. " Class: ". get_class($chainItem->getValidator())."\n";
}

dump(ValidatorChainExprDumper::dump($nChain));

echo "Validate: 'a'\n";

$nChain->validate('a');
echo "OK!\n";

echo "Validate: '@'\n";

$nChain->validate('@');
echo "OK!\n";

echo "Validate: 0\n";

$nChain->validate(0);
echo "OK!\n";