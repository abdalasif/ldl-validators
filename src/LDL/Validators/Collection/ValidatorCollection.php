<?php declare(strict_types=1);

namespace LDL\Validators\Collection;

use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Framework\Base\Collection\Traits\AppendableInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\AppendManyTrait;
use LDL\Framework\Base\Collection\Traits\CollectionInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\LockAppendInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\LockRemoveInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\RemovableInterfaceTrait;
use LDL\Framework\Base\Traits\LockableObjectInterfaceTrait;
use LDL\Validators\Chain\ValidatorChainInterface;
use LDL\Validators\ValidatorInterface;

class ValidatorCollection implements ValidatorCollectionInterface {

    use AppendableInterfaceTrait {append as private _append;}
    use CollectionInterfaceTrait;
    use AppendManyTrait;
    use RemovableInterfaceTrait;
    use LockAppendInterfaceTrait;
    use LockRemoveInterfaceTrait;
    use LockableObjectInterfaceTrait;

    public function append($item, $key = null): CollectionInterface
    {
        if(!$item instanceof ValidatorInterface){
            $msg = sprintf('Item to be added must be an instance of %s', ValidatorInterface::class);
            throw new \InvalidArgumentException($msg);
        }

        return $this->_append($item, $key);
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

}
