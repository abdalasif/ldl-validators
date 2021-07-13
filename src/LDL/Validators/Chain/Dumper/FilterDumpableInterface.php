<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Dumper;

use LDL\Validators\Chain\ValidatorChainInterface;

interface FilterDumpableInterface
{
    /**
     * @return ValidatorChainInterface
     */
    public function filterDumpableItems() : ValidatorChainInterface;
}