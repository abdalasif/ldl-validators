<?php declare(strict_types=1);

namespace LDL\Validators\Chain;

use LDL\Framework\Base\Collection\CallableCollection;
use LDL\Validators\Chain\Item\Collection\ValidatorChainItemCollection;
use LDL\Validators\Chain\Item\Collection\ValidatorChainItemCollectionInterface;
use LDL\Validators\Chain\Item\ValidatorChainItemInterface;
use LDL\Validators\Traits\ValidatorBeforeValidateTrait;
use LDL\Validators\Traits\ValidatorDescriptionTrait;

abstract class AbstractValidatorChain implements ValidatorChainInterface
{
    use ValidatorDescriptionTrait;
    use ValidatorBeforeValidateTrait;

    /**
     * @var ValidatorChainItemCollectionInterface
     */
    private $chainItems;

    /**
     * @var ValidatorChainItemCollectionInterface
     */
    private $succeeded;

    /**
     * @var ValidatorChainItemCollectionInterface
     */
    private $failed;

    /**
     * @var ValidatorChainItemInterface
     */
    private $lastExecuted;

    /**
     * @var bool
     */
    private $changed;

    public function __construct(
        string $description,
        iterable $validators=null
    )
    {
        $this->succeeded = new ValidatorChainItemCollection();
        $this->failed = new ValidatorChainItemCollection();
        $this->_tDescription = $description;

        $this->chainItems = new ValidatorChainItemCollection(
            $validators,
            new CallableCollection([
                function(){
                    $this->changed = true;
                }
            ]),
            new CallableCollection([
                function(){
                    $this->changed = true;
                }
            ]),
            new CallableCollection([
                function(){
                    $this->changed = true;
                }
            ])
        );

        $this->onBeforeValidate()->append(function(){
            $this->lastExecuted = null;
            $this->failed = new ValidatorChainItemCollection();
            $this->succeeded = new ValidatorChainItemCollection();
            $this->changed = false;
        });
    }

    public static function factory(
        string $description=null,
        iterable $validators=null,
        ...$params
    ) : ValidatorChainInterface
    {
        return new static($description, $validators);
    }

    public function getSucceeded() : ValidatorChainItemCollectionInterface
    {
        return $this->succeeded;
    }

    public function getFailed() : ValidatorChainItemCollectionInterface
    {
        return $this->failed;
    }

    public function getLastExecuted(): ?ValidatorChainItemInterface
    {
        return $this->lastExecuted;
    }

    public function getChainItems(): ValidatorChainItemCollectionInterface
    {
        return $this->chainItems;
    }

    //<editor-fold desc="Protected methods">
    protected function setLastExecuted(ValidatorChainItemInterface $validator): ValidatorChainInterface
    {
        $this->lastExecuted = $validator;
        return $this;
    }

    protected function isChanged(): bool
    {
        return (bool) $this->changed;
    }
    //</editor-fold>
}
