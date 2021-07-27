<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Item\Collection;

use LDL\Framework\Base\Collection\Contracts\AppendableInterface;
use LDL\Framework\Base\Collection\Contracts\BeforeAppendInterface;
use LDL\Framework\Base\Collection\Contracts\BeforeRemoveInterface;
use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Framework\Base\Collection\Contracts\LockAppendInterface;
use LDL\Framework\Base\Collection\Contracts\LockRemoveInterface;
use LDL\Framework\Base\Collection\Contracts\LockReplaceInterface;
use LDL\Framework\Base\Collection\Contracts\RemovableInterface;
use LDL\Framework\Base\Collection\Contracts\UnshiftInterface;
use LDL\Framework\Base\Contracts\LockableObjectInterface;
use LDL\Validators\Chain\Dumper\FilterDumpableInterface;
use LDL\Validators\Collection\ValidatorCollectionInterface;

interface ValidatorChainItemCollectionInterface extends CollectionInterface, LockableObjectInterface, AppendableInterface, BeforeAppendInterface, BeforeRemoveInterface, LockAppendInterface, LockReplaceInterface, LockRemoveInterface, UnshiftInterface, RemovableInterface, FilterDumpableInterface
{

    /**
     * Must return a one dimension ValidatorCollectionInterface
     * @return ValidatorCollectionInterface
     */
    public function getValidators() : ValidatorCollectionInterface;

}