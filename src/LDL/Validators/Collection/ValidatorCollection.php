<?php declare(strict_types=1);

namespace LDL\Validators\Collection;

use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Framework\Base\Collection\Traits\AppendableInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\AppendManyTrait;
use LDL\Framework\Base\Collection\Traits\BeforeAppendInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\BeforeRemoveInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\BeforeReplaceInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\BeforeResolveKeyInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\CollectionInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\FilterByClassInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\FilterByInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\LockAppendInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\LockRemoveInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\RemovableInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\ReplaceByKeyInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\ReplaceEqualValueInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\AppendInPositionInterfaceTrait;
use LDL\Framework\Base\Traits\LockableObjectInterfaceTrait;
use LDL\Validators\BeforeValidateInterface;
use LDL\Validators\Chain\ValidatorChainInterface;
use LDL\Validators\InterfaceComplianceValidator;
use LDL\Validators\ValidatorInterface;

class ValidatorCollection implements ValidatorCollectionInterface {

    use CollectionInterfaceTrait;
    use AppendableInterfaceTrait;
    use BeforeResolveKeyInterfaceTrait;
    use BeforeReplaceInterfaceTrait;
    use BeforeRemoveInterfaceTrait;
    use AppendManyTrait;
    use RemovableInterfaceTrait;
    use LockAppendInterfaceTrait;
    use LockRemoveInterfaceTrait;
    use LockableObjectInterfaceTrait;
    use FilterByInterfaceTrait;
    use FilterByClassInterfaceTrait;
    use ReplaceByKeyInterfaceTrait;
    use AppendInPositionInterfaceTrait;
    use ReplaceEqualValueInterfaceTrait;

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
         * NOTE: Before resolve key is used instead of before append due to performance
         * ("before resolve key" happens before "before append" and thus has less overhead)
         */
        $this->getBeforeResolveKey()->append(static function ($collection, $item, $key) {
            (new InterfaceComplianceValidator(ValidatorInterface::class))
                ->validate($item);
        })
        ->append(function ($collection, $item, $key){
            $this->changed = true;
        })->lock();

        $this->getBeforeReplace()->append(function($collection, $item, $key){
            $this->changed = true;
        })->lock();

        $this->getBeforeRemove()->append(function($collection, $item, $key){
            $this->changed = true;
        })->lock();

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
