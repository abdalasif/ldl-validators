<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Dumper;

use LDL\Validators\Chain\Item\Collection\ValidatorChainItemCollectionInterface;

interface FilterDumpableInterface
{
    /**
     * @return ValidatorChainItemCollectionInterface
     */
    public function filterDumpableItems() : ValidatorChainItemCollectionInterface;
}