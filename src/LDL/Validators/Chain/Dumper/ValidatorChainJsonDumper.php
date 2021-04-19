<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Dumper;

use LDL\Validators\Chain\ValidatorChainInterface;

class ValidatorChainJsonDumper implements ValidatorChainDumperInterface
{
    public static function dump(ValidatorChainInterface $collection, int $options=null) : string
    {
        $defaultOptions = \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT;

        return json_encode(
            ValidatorChainPhpDumper::dump($collection),
            $options ?? $defaultOptions
        );
    }
}
