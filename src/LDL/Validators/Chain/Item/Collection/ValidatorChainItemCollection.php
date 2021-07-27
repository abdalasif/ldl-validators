<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Item\Collection;

use LDL\Framework\Base\Collection\CallableCollectionInterface;
use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Framework\Base\Collection\Traits\AppendableInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\AppendManyTrait;
use LDL\Framework\Base\Collection\Traits\BeforeAppendInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\BeforeRemoveInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\CollectionInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\LockAppendInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\LockRemoveInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\LockReplaceInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\RemovableInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\UnshiftInterfaceTrait;
use LDL\Framework\Base\Traits\LockableObjectInterfaceTrait;
use LDL\Framework\Helper\IterableHelper;
use LDL\Validators\Chain\Item\ValidatorChainItem;
use LDL\Validators\Chain\Item\ValidatorChainItemInterface;
use LDL\Validators\Chain\Traits\FilterDumpableInterfaceTrait;
use LDL\Validators\Chain\ValidatorChainInterface;
use LDL\Validators\Collection\ValidatorCollection;
use LDL\Validators\Collection\ValidatorCollectionInterface;
use LDL\Validators\InterfaceComplianceValidator;
use LDL\Validators\ValidatorInterface;

class ValidatorChainItemCollection implements ValidatorChainItemCollectionInterface
{
    use CollectionInterfaceTrait;
    use AppendableInterfaceTrait {append as _append;}
    use AppendManyTrait;
    use BeforeAppendInterfaceTrait;
    use LockableObjectInterfaceTrait;
    use LockReplaceInterfaceTrait;
    use LockAppendInterfaceTrait;
    use LockRemoveInterfaceTrait;
    use RemovableInterfaceTrait;
    use BeforeRemoveInterfaceTrait;
    use UnshiftInterfaceTrait {unshift as _unshift;}
    use FilterDumpableInterfaceTrait;

    /**
     * @var CallableCollectionInterface|null
     */
    private $callableCollection;

    public function __construct(
        iterable $items=null,
        CallableCollectionInterface $beforeAppend = null,
        CallableCollectionInterface $beforeRemove = null
    )
    {
        /**
         * Each item within validator chains must be an instance of ValidatorChainItemInterface
         */
        $this->getBeforeAppend()->append(static function ($collection, $item, $key){
            (new InterfaceComplianceValidator(ValidatorChainItemInterface::class))
                ->validate($item);
        });

        if(null !== $beforeAppend){
            $this->getBeforeAppend()->appendMany($beforeAppend);
        }

        if(null !== $beforeRemove){
            $this->getBeforeRemove()->appendMany($beforeRemove);
        }

        if(null !== $items){
            $this->appendMany($items,true);
        }
    }

    /**
     * Allow appending validators directly, decorate validators through ValidatorChainItem
     *
     * @param mixed $item
     * @param null $key
     * @return CollectionInterface
     */
    public function append($item, $key = null): CollectionInterface
    {
        if ($item instanceof ValidatorInterface) {
            $item = new ValidatorChainItem($item, true);
        }

        $this->_append($item, $key);

        return $this;
    }

    public function unshift($item, $key = null): CollectionInterface
    {
        if ($item instanceof ValidatorInterface) {
            $item = new ValidatorChainItem($item, true);
        }

        $this->_unshift($item, $key);

        return $this;
    }

    public function getValidators() : ValidatorCollectionInterface
    {
        $collection = new ValidatorCollection();

        IterableHelper::map($this, static function ($item) use ($collection){
            $validator = $item->getValidator();

            if($validator instanceof ValidatorChainInterface){
                return $collection->appendMany($validator->getChainItems()->getValidators()->toArray());
            }

            return $collection->append($validator);
        });

        return $collection;
    }

}