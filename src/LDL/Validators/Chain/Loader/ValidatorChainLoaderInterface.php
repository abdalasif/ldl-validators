<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Loader;

use LDL\Validators\Chain\ValidatorChainInterface;

interface ValidatorChainLoaderInterface
{

    public static function load(array $data) : ValidatorChainInterface;

}