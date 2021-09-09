<?php declare(strict_types=1);

namespace LDL\Validators\Collection;

use LDL\Framework\Base\Collection\Contracts\AppendableInterface;
use LDL\Framework\Base\Collection\Contracts\BeforeAppendInterface;
use LDL\Framework\Base\Collection\Contracts\BeforeRemoveInterface;
use LDL\Framework\Base\Collection\Contracts\BeforeReplaceInterface;
use LDL\Framework\Base\Collection\Contracts\BeforeResolveKeyInterface;
use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Framework\Base\Collection\Contracts\FilterByClassInterface;
use LDL\Framework\Base\Collection\Contracts\FilterByInterface;
use LDL\Framework\Base\Collection\Contracts\LockAppendInterface;
use LDL\Framework\Base\Collection\Contracts\LockRemoveInterface;
use LDL\Framework\Base\Collection\Contracts\RemoveByKeyInterface;
use LDL\Framework\Base\Collection\Contracts\ReplaceByKeyInterface;
use LDL\Framework\Base\Collection\Contracts\AppendInPositionInterface;
use LDL\Framework\Base\Contracts\LockableObjectInterface;
use LDL\Validators\Chain\ValidatorChainInterface;

interface ValidatorCollectionInterface extends CollectionInterface, AppendableInterface, BeforeReplaceInterface, BeforeResolveKeyInterface, LockAppendInterface, BeforeRemoveInterface, RemoveByKeyInterface, LockRemoveInterface, LockableObjectInterface, FilterByInterface, FilterByClassInterface, ReplaceByKeyInterface, AppendInPositionInterface
{
    /**
     * @param string $class
     * @param mixed ...$params
     * @return ValidatorChainInterface
     */
    public function getChain(string $class, ...$params) : ValidatorChainInterface;

    /**
     * @param mixed ...$params
     */
    public function onBeforeValidate(...$params) : void;
}