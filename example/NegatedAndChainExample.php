<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';


use LDL\Validators\RegexValidator;
use LDL\Validators\Chain\AndValidatorChain;
use LDL\Validators\StringValidator;

echo "Create AndValidatorChain\n";
echo "Append StringValidator && RegexValidator(#[a-z]+#)\n";

$chain = new AndValidatorChain([
    new StringValidator(),
    new RegexValidator('#[a-z]+#')
]);

dump(\LDL\Validators\Chain\Dumper\ValidatorChainExprDumper::dump($chain));

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
], true);

dump(\LDL\Validators\Chain\Dumper\ValidatorChainExprDumper::dump($nChain));

echo "Validate: 'a' (exception must be thrown)\n";
try{
    $nChain->validate('a');
}catch(\Exception $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Validate: '@'\n";

try{
    $nChain->validate('@');
    echo "OK!\n";
}catch(\Exception $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Validate: 0\n";

try{
    $nChain->validate(0);
    echo "OK!\n";
}catch(\Exception $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Remove StringValidator\n";
$nChain->remove(0);

echo "Append StringValidator negated\n";
$nChain->append(new StringValidator(true));

dump(\LDL\Validators\Chain\Dumper\ValidatorChainExprDumper::dump($nChain));

echo "Validate: 'a'\n";

try{
    $nChain->validate('a');
    echo "OK!\n";
}catch(\Exception $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Validate: '@'\n";

try{
    $nChain->validate('@');
    echo "OK!\n";
}catch(\Exception $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

echo "Validate: 0\n";

try{
    $nChain->validate(0);
    echo "OK!\n";
}catch(\Exception $e){
    echo "EXCEPTION: {$e->getMessage()}\n";
}

