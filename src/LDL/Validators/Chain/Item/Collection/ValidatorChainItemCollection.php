<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Item\Collection;

use LDL\Framework\Base\Collection\CallableCollectionInterface;
use LDL\Framework\Base\Collection\Traits\AppendableInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\AppendInPositionInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\AppendManyTrait;
use LDL\Framework\Base\Collection\Traits\BeforeAppendInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\BeforeRemoveInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\BeforeReplaceInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\BeforeResolveKeyInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\CollectionInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\LockAppendInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\LockRemoveInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\LockReplaceInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\RemoveByKeyInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\ReplaceByKeyInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\ReplaceEqualValueInterfaceTrait;
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
    use BeforeResolveKeyInterfaceTrait;
    use AppendableInterfaceTrait;
    use AppendInPositionInterfaceTrait;
    use AppendManyTrait;
    use BeforeAppendInterfaceTrait;
    use LockableObjectInterfaceTrait;
    use ReplaceEqualValueInterfaceTrait;
    use ReplaceByKeyInterfaceTrait;
    use BeforeReplaceInterfaceTrait;
    use LockReplaceInterfaceTrait;
    use LockAppendInterfaceTrait;
    use LockRemoveInterfaceTrait;
    use RemoveByKeyInterfaceTrait;
    use BeforeRemoveInterfaceTrait;
    use FilterDumpableInterfaceTrait;

    /**
     * @var CallableCollectionInterface|null
     */
    private $callableCollection;

    public function __construct(
        iterable $items=null,
        CallableCollectionInterface $beforeResolveKey= null,
        CallableCollectionInterface $beforeRemove = null,
        CallableCollectionInterface $beforeReplace = null
    )
    {
        /**
         * Each item within validator chains must be an instance of ValidatorChainItemInterface
         *
         * NOTE: Validation of the item is added on "before resolve key" to check early for possible errors
         * before append happens after they key has been resolved possibly incurring into a bit more overhead,
         * that is why this is done here to save some execution time and throw early.
         */
        $this->getBeforeResolveKey()->append(static function ($collection, &$item, $key){
          if ($item instanceof ValidatorInterface) {
                $item = new ValidatorChainItem($item, true);
          }
        })
        ->append(static function ($collection, $item, $key){
            (new InterfaceComplianceValidator(ValidatorChainItemInterface::class))
                ->validate($item);
        });

        if(null !== $beforeResolveKey){
            $this->getBeforeResolveKey()->appendMany($beforeResolveKey);
        }

        $this->getBeforeResolveKey()->lock();

        if(null !== $beforeRemove){
            $this->getBeforeRemove()->appendMany($beforeRemove);
        }

        $this->getBeforeRemove()->lock();

        $this->getBeforeReplace()->append(static function ($collection, $item, $key){
            (new InterfaceComplianceValidator(ValidatorChainItemInterface::class))
                ->validate($item);
        });

        if(null !== $beforeReplace){
            $this->getBeforeReplace()->appendMany($beforeReplace);
        }

        if(null !== $items){
            $this->appendMany($items,true);
        }
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