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
use LDL\Validators\Collection\ValidatorCollectionInterface;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\ValidatorInterface;

interface ValidatorChainInterface extends ValidatorInterface, CollectionInterface, LockableObjectInterface, BeforeAppendInterface, AppendableInterface, LockAppendInterface, BeforeRemoveInterface, RemovableInterface, FilterByInterface, FilterByClassInterface
{
    /**
     * Validator chain Factory method.
     *
     * This method exists due that the __construct method can not be trusted
     * since it's not part of the interface, in simpler words, there is no guarantee that the first argument of
     * __construct will be iterable $validators.
     *
     * @param iterable|null $validators
     * @param mixed ...$params
     * @return ValidatorChainInterface
     */
    public static function factory(iterable $validators=null, ...$params) : ValidatorChainInterface;

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

    /**
     * @param ValidatorConfigInterface $config
     * @param iterable $validators
     * @return ValidatorChainInterface
     */
    public static function fromConfig(ValidatorConfigInterface $config, iterable $validators=null) : ValidatorChainInterface;

    /**
     * @return ValidatorCollectionInterface
     */
    public function getCollection() : ValidatorCollectionInterface;

}