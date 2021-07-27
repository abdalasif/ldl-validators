<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Validators\Chain\Item\ValidatorChainItem;
use LDL\Validators\RegexValidator;
use LDL\Validators\StringValidator;
use LDL\Validators\Chain\Item\Collection\ValidatorChainItemCollection;

echo "Create ValidatorChainItemCollection\n";
echo "Append StringValidator (NOT dumpable) && RegexValidator(#[a-z]+#)\n";

$chain = new ValidatorChainItemCollection([
    new ValidatorChainItem(new StringValidator(true), false),
    new RegexValidator('#[a-z]+#')
]);

echo "Verify validators\n";

foreach($chain as $chainItem){
    echo get_class($chainItem->getValidator())."\n";
    echo "Validator is dumpable?\n";
    dump($chainItem->isDumpable());
}