<?php declare(strict_types=1);

namespace LDL\Validators\Chain;

use LDL\Validators\BeforeValidateInterface;
use LDL\Validators\Chain\Item\Collection\ValidatorChainItemCollectionInterface;
use LDL\Validators\Chain\Item\ValidatorChainItemInterface;
use LDL\Validators\ValidatorInterface;

interface ValidatorChainInterface extends ValidatorInterface, BeforeValidateInterface
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
     * @return ValidatorChainItemCollectionInterface
     */
    public function getSucceeded() : ValidatorChainItemCollectionInterface;

    /**
     * @return ValidatorChainItemCollectionInterface
     */
    public function getFailed() : ValidatorChainItemCollectionInterface;

    /**
     * @return ValidatorChainItemInterface|null
     */
    public function getLastExecuted(): ?ValidatorChainItemInterface;

    /**
     * @return ValidatorChainItemCollectionInterface
     */
    public function getChainItems(): ValidatorChainItemCollectionInterface;

}