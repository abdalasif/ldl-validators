<?php declare(strict_types=1);

namespace LDL\Validators\Collection;

use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Framework\Base\Collection\Traits\AppendableInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\AppendManyTrait;
use LDL\Framework\Base\Collection\Traits\BeforeAppendInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\CollectionInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\FilterByClassInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\FilterByInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\LockAppendInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\LockRemoveInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\RemovableInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\ReplaceableInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\UnshiftInterfaceTrait;
use LDL\Framework\Base\Traits\LockableObjectInterfaceTrait;
use LDL\Validators\BeforeValidateInterface;
use LDL\Validators\Chain\ValidatorChainInterface;
use LDL\Validators\InterfaceComplianceValidator;
use LDL\Validators\ValidatorInterface;

class ValidatorCollection implements ValidatorCollectionInterface {

    use CollectionInterfaceTrait;
    use AppendableInterfaceTrait {append as private _append;}
    use BeforeAppendInterfaceTrait;
    use AppendManyTrait;
    use RemovableInterfaceTrait {remove as private _remove;}
    use LockAppendInterfaceTrait;
    use LockRemoveInterfaceTrait;
    use LockableObjectInterfaceTrait;
    use FilterByInterfaceTrait;
    use FilterByClassInterfaceTrait;
    use ReplaceableInterfaceTrait {replace as private _replace;}
    use UnshiftInterfaceTrait {unshift as private _unshift;}

    /**
     * @var CollectionInterface
     */
    private $beforeValidateCollection;

    /**
     * @var bool
     */
    private $changed;

    public function __construct(iterable $items=null)
    {
        /**
         * Each item within validator chains must be an instance of ValidatorInterface
         */
        $this->getBeforeAppend()->append(static function ($collection, $item, $key) {
            (new InterfaceComplianceValidator(ValidatorInterface::class))
                ->validate($item);
        });

        if(null !== $items){
            $this->appendMany($items);
        }

        $this->filterBeforeValidate();
    }

    public function getChain(string $class, ...$params) : ValidatorChainInterface
    {
        if(!$class instanceof ValidatorChainInterface){
            throw new \InvalidArgumentException(
                sprintf(
                    'Given class must be an instance of "%s", however "%s" was given',
                    ValidatorChainInterface::class,
                    $class
                )
            );
        }

        return $class::factory(\iterator_to_array($this), ...$params);
    }

    public function append($item, $key = null): CollectionInterface
    {
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

    public function replace($item, $key): CollectionInterface
    {
        $this->_replace($item, $key);
        $this->changed = true;

        return $this;
    }

    public function unshift($item, $key = null): CollectionInterface
    {
        $this->_unshift($item, $key);
        $this->changed = true;

        return $this;
    }

    public function onBeforeValidate(...$params): void
    {
        if($this->changed){
            $this->filterBeforeValidate();
        }

        /**
         * @var BeforeValidateInterface $validator
         */
        foreach($this->beforeValidateCollection as $validator){
            $validator->onBeforeValidate()->call(...$params);
        }
    }

    private function filterBeforeValidate(): void
    {
        $this->beforeValidateCollection = $this->filterByInterfaceRecursive(BeforeValidateInterface::class);
        $this->changed = false;
    }
}
