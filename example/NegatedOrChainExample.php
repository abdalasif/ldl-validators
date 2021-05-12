<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';


use LDL\Validators\RegexValidator;
use LDL\Validators\Chain\OrValidatorChain;
use LDL\Validators\Chain\Exception\CombinedException;

echo "Create NEGATED OrValidatorChain\n";

echo "Append RegexValidator, with regex: '#[a-z]+#'\n";
echo "Append RegexValidator, with regex: '#[0-9]+#'\n";

$chain = new OrValidatorChain([
    new RegexValidator('#[a-z]+#'),
    new RegexValidator('#[0-9]+#')
], true);

dump(\LDL\Validators\Chain\Dumper\ValidatorChainExprDumper::dump($chain));

echo "Validate: 'a'\n";

try{
    $chain->validate('a');
}catch(CombinedException $e){
    echo "EXCEPTION: {$e->getCombinedMessage()}\n";
}
