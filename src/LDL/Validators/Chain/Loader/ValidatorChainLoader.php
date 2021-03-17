<?php declare(strict_types=1);

namespace LDL\Validators\Collection\Validator\Chain\Loader;

use LDL\Validators\Collection\Interfaces\Validation\HasKeyValidatorChainInterface;
use LDL\Validators\Collection\Validator\Chain\Config\Item\ValidatorChainConfigItem;
use LDL\Validators\Collection\Validator\KeyValidatorChain;
use LDL\Validators\Collection\Validator\ValidatorChain;

class ValidatorChainLoader
{
    public static function loadValueChain(array $data) : ValidatorChain
    {
        $return = new ValidatorChain();

        foreach($data as $item){
            $config = ValidatorChainConfigItem::fromArray($item);
            $return->append($config->getValidatorInstance());
        }

        return $return;
    }

    public static function loadKeyChain(array $data)
    {
        $return = new KeyValidatorChain();

        foreach($data as $item){
            $config = ValidatorChainConfigItem::fromArray($item);
            $return->append($config->getValidatorInstance());
        }

        return $return;
    }
}