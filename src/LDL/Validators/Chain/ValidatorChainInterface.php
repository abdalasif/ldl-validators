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
     * This method exists due that the __construct method varies according to the implementation
     * (it's not part of the interface), in simpler words, there is no guarantee that the first argument of
     * __construct will be a description or that the second argument will be a set of iterable $validators.
     *
     * As we can't add __construct to this interface this method exists for this very same reason.
     *
     * @param string|null $description
     * @param iterable|null $validators
     * @param mixed ...$params
     * @return ValidatorChainInterface
     */
    public static function factory(
        string $description = null,
        iterable $validators = null,
        ...$params
    ) : ValidatorChainInterface;

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