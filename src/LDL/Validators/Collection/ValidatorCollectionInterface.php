<?php declare(strict_types=1);

namespace LDL\Validators\Collection;

use LDL\Framework\Base\Collection\Contracts\AppendableInterface;
use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Framework\Base\Collection\Contracts\FilterByClassInterface;
use LDL\Framework\Base\Collection\Contracts\FilterByInterface;
use LDL\Framework\Base\Collection\Contracts\LockAppendInterface;
use LDL\Framework\Base\Collection\Contracts\LockRemoveInterface;
use LDL\Framework\Base\Collection\Contracts\RemovableInterface;
use LDL\Framework\Base\Contracts\LockableObjectInterface;
use LDL\Validators\Chain\ValidatorChainInterface;

interface ValidatorCollectionInterface extends CollectionInterface, AppendableInterface, LockAppendInterface, RemovableInterface, LockRemoveInterface, LockableObjectInterface, FilterByInterface, FilterByClassInterface
{
    /**
     * @param string $class
     * @param mixed ...$params
     * @return ValidatorChainInterface
     */
    public function getChain(string $class, ...$params) : ValidatorChainInterface;
}