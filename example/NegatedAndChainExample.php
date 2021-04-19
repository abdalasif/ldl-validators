<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';


use LDL\Validators\RegexValidator;
use LDL\Validators\Chain\AndValidatorChain;
use LDL\Validators\StringValidator;

echo "Create Validator Chain\n";

$chain = new AndValidatorChain([
    new StringValidator(),
    new RegexValidator('#[a-z]+#')
], true);


try{
    $chain->validate([]);
}catch(\Exception $e){
    dd($e->getMessage());
}