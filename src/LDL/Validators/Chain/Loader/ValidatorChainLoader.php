<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Loader;

use LDL\Validators\Chain\AndValidatorChain;
use LDL\Validators\Chain\ValidatorChainInterface;

class ValidatorChainLoader
{
    public static function load(array $data) : ValidatorChainInterface
    {
        $chain = new AndValidatorChain([]);

        foreach($data as $item){
            if(false === array_key_exists('config', $item)){
                $chain->append(new $item['validator'], $item['key']);
                continue;
            }

            $config = $item['config']['class']::fromArray($item['config']['config']);
            $chain->append($item['validator']::fromConfig($config), $item['key']);
        }

        return $chain;
    }
}