<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Dumper;

use LDL\Validators\Chain\ValidatorChainInterface;

interface ValidatorChainDumperInterface
{
    public static function dump(ValidatorChainInterface $collection, string $file) : void;
}