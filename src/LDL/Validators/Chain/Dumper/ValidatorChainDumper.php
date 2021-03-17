<?php declare(strict_types=1);

namespace LDL\Validators\Collection\Validator\Chain\Dumper;

use LDL\Validators\Collection\Interfaces\Validation\HasKeyValidatorChainInterface;
use LDL\Validators\Collection\Interfaces\Validation\HasValueValidatorChainInterface;

class ValidatorChainDumper
{
    public static function dumpValueChain(
        HasValueValidatorChainInterface $collection,
        string $file
    ) : void
    {
        $fp = fopen($file, 'wb');
        fwrite($fp, json_encode($collection->getValueValidatorChain()->getConfig(), \JSON_THROW_ON_ERROR));
        fclose($fp);
    }

    public static function dumpKeyChain(
        HasKeyValidatorChainInterface $collection,
        string $file
    ) : void
    {
        $fp = fopen($file, 'wb');
        fwrite($fp, json_encode($collection->getKeyValidatorChain()->getConfig(), \JSON_THROW_ON_ERROR));
        fclose($fp);
    }

}
