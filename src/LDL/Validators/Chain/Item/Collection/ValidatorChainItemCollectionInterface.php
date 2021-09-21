<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Item\Collection;

use LDL\Framework\Base\Collection\Contracts\AppendableInterface;
use LDL\Framework\Base\Collection\Contracts\AppendInPositionInterface;
use LDL\Framework\Base\Collection\Contracts\BeforeAppendInterface;
use LDL\Framework\Base\Collection\Contracts\BeforeRemoveInterface;
use LDL\Framework\Base\Collection\Contracts\BeforeReplaceInterface;
use LDL\Framework\Base\Collection\Contracts\BeforeResolveKeyInterface;
use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Framework\Base\Collection\Contracts\LockAppendInterface;
use LDL\Framework\Base\Collection\Contracts\LockRemoveInterface;
use LDL\Framework\Base\Collection\Contracts\LockReplaceInterface;
use LDL\Framework\Base\Collection\Contracts\ReplaceByKeyInterface;
use LDL\Framework\Base\Collection\Contracts\RemoveByKeyInterface;
use LDL\Framework\Base\Collection\Contracts\ReplaceByValueInterface;
use LDL\Framework\Base\Contracts\LockableObjectInterface;
use LDL\Validators\Chain\Dumper\FilterDumpableInterface;
use LDL\Validators\Collection\ValidatorCollectionInterface;

interface ValidatorChainItemCollectionInterface extends CollectionInterface, LockableObjectInterface, AppendableInterface, AppendInPositionInterface, BeforeAppendInterface, BeforeResolveKeyInterface, BeforeRemoveInterface, LockAppendInterface, LockReplaceInterface, LockRemoveInterface, RemoveByKeyInterface, FilterDumpableInterface, ReplaceByKeyInterface, ReplaceByValueInterface, BeforeReplaceInterface
{

    /**
     * Must return a one dimension ValidatorCollectionInterface
     * @return ValidatorCollectionInterface
     */
    public function getValidators() : ValidatorCollectionInterface;

}