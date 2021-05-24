<?php declare(strict_types=1);

namespace LDL\Validators\Chain;

use LDL\Framework\Base\Collection\Contracts\AppendableInterface;
use LDL\Framework\Base\Collection\Contracts\BeforeAppendInterface;
use LDL\Framework\Base\Collection\Contracts\BeforeRemoveInterface;
use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Framework\Base\Collection\Contracts\FilterByClassInterface;
use LDL\Framework\Base\Collection\Contracts\FilterByInterface;
use LDL\Framework\Base\Collection\Contracts\LockAppendInterface;
use LDL\Framework\Base\Collection\Contracts\RemovableInterface;
use LDL\Framework\Base\Contracts\LockableObjectInterface;
use LDL\Validators\Chain\Config\ValidatorChainConfig;
use LDL\Validators\ValidatorInterface;

interface ValidatorChainInterface extends ValidatorInterface, CollectionInterface, LockableObjectInterface, BeforeAppendInterface, AppendableInterface, LockAppendInterface, BeforeRemoveInterface, RemovableInterface, FilterByInterface, FilterByClassInterface
{
    /**
     * @return ValidatorChainInterface
     */
    public function filterDumpableItems() : ValidatorChainInterface;

    /**
     * @return ValidatorChainInterface
     */
    public function getSucceeded() : ValidatorChainInterface;

    /**
     * @return ValidatorChainInterface
     */
    public function getFailed() : ValidatorChainInterface;

    /**
     * @return ValidatorInterface|null
     */
    public function getLastExecuted(): ?ValidatorInterface;

    /**
     * @return ValidatorChainConfig
     */
    public function getConfig() : ValidatorChainConfig;
}