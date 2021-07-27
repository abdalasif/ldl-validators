<?php declare(strict_types=1);

namespace LDL\Validators\Collection;

use LDL\Framework\Base\Collection\Traits\AppendableInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\AppendManyTrait;
use LDL\Framework\Base\Collection\Traits\BeforeAppendInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\CollectionInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\FilterByClassInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\FilterByInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\LockAppendInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\LockRemoveInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\RemovableInterfaceTrait;
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
    use RemovableInterfaceTrait;
    use LockAppendInterfaceTrait;
    use LockRemoveInterfaceTrait;
    use LockableObjectInterfaceTrait;
    use FilterByInterfaceTrait;
    use FilterByClassInterfaceTrait;

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
        /**
         * @var BeforeValidateInterface $validator
         */
        foreach($this->filterByInterfaceRecursive(BeforeValidateInterface::class) as $validator){
            $validator->onBeforeValidate()->call(...$params);
        }
    }

}
