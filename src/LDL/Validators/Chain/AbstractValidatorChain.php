<?php declare(strict_types=1);

namespace LDL\Validators\Chain;

use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Framework\Base\Collection\Traits\AppendableInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\AppendManyTrait;
use LDL\Framework\Base\Collection\Traits\BeforeAppendInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\BeforeRemoveInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\CollectionInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\FilterByClassInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\FilterByInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\LockAppendInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\RemovableInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\UnshiftInterfaceTrait;
use LDL\Framework\Base\Traits\LockableObjectInterfaceTrait;
use LDL\Validators\Chain\Config\ValidatorChainConfig;
use LDL\Validators\Chain\Item\ValidatorChainItem;
use LDL\Validators\Chain\Item\ValidatorChainItemInterface;
use LDL\Validators\Collection\ValidatorCollection;
use LDL\Validators\Collection\ValidatorCollectionInterface;
use LDL\Validators\InterfaceComplianceValidator;
use LDL\Validators\ResetValidatorInterface;
use LDL\Validators\Traits\ValidatorDescriptionTrait;
use LDL\Validators\ValidatorInterface;

abstract class AbstractValidatorChain implements ValidatorChainInterface
{
    use CollectionInterfaceTrait;
    use LockableObjectInterfaceTrait;
    use BeforeAppendInterfaceTrait;
    use AppendableInterfaceTrait {append as _append;}
    use AppendManyTrait;
    use LockAppendInterfaceTrait;
    use BeforeRemoveInterfaceTrait;
    use RemovableInterfaceTrait {remove as _remove;}
    use FilterByInterfaceTrait;
    use FilterByClassInterfaceTrait;
    use UnshiftInterfaceTrait;
    use ValidatorDescriptionTrait;

    private const DESCRIPTION = 'Abstract Validator chain';

    /**
     * @var ValidatorCollectionInterface
     */
    private $succeeded;

    /**
     * @var ValidatorCollectionInterface
     */
    private $failed;

    /**
     * @var ValidatorChainItemInterface
     */
    private $lastExecuted;

    /**
     * @var Config\ValidatorChainConfig
     */
    private $config;

    /**
     * @var ValidatorCollectionInterface
     */
    private $resetValidatorsCollection;

    /**
     * @var bool
     */
    private $changed;

    public function __construct(
        iterable $validators=null,
        string $description=null
    )
    {
        $this->getBeforeAppend()->append(static function ($collection, $item, $key){
            (new InterfaceComplianceValidator(ValidatorChainItemInterface::class))->validate($item);
        });

        if(null !== $validators) {
            $this->appendMany($validators, false);
        }

        $this->config = new Config\ValidatorChainConfig(static::OPERATOR);
        $this->succeeded = new ValidatorCollection();
        $this->failed = new ValidatorCollection();
        $this->_tDescription = $description ?? self::DESCRIPTION;
    }

    public function append($item, $key = null): CollectionInterface
    {
        if ($item instanceof ValidatorInterface) {
            $item = new ValidatorChainItem($item, true);
        }

        $this->_append($item, $key);
        $this->changed = true;

        return $this;
    }

    public function remove($key): CollectionInterface
    {
        $this->_remove($key);
        $this->changed = true;

        return $this;
    }

    public static function factory(iterable $validators=null, ...$params) : ValidatorChainInterface
    {
        return new static($validators, ...$params);
    }

    public function getSucceeded() : ValidatorCollectionInterface
    {
        return $this->succeeded;
    }

    public function getFailed() : ValidatorCollectionInterface
    {
        return $this->failed;
    }

    public function getLastExecuted(): ?ValidatorChainItemInterface
    {
        return $this->lastExecuted;
    }

    public function getConfig() : ValidatorChainConfig
    {
        return $this->config;
    }

    public function getCollection() : ValidatorCollectionInterface
    {
        return new ValidatorCollection($this->toArray());
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

    protected function setChanged(bool $changed): ValidatorChainInterface
    {
        $this->changed = $changed;
        return $this;
    }

    protected function resetTracking(): void
    {
        $this->lastExecuted = null;
        $this->failed = new ValidatorCollection();
        $this->succeeded = new ValidatorCollection();
    }

    protected function resetValidators(): void
    {
        if(!$this->resetValidatorsCollection){
            return;
        }

        foreach($this->resetValidatorsCollection as $validator){
            $validator->reset();
        }
    }

    protected function filterResetValidators(): void
    {
        $this->resetValidatorsCollection = $this->filterByInterfaceRecursive(ResetValidatorInterface::class);
        $this->changed = false;
    }

    protected function reset(): void
    {
        $this->resetTracking();

        if($this->isChanged()){
            $this->filterResetValidators();
        }

        $this->resetValidators();
    }
    //</editor-fold>
}
