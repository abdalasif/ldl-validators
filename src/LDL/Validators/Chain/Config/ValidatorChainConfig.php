<?php declare(strict_types=1);

namespace LDL\Validators\Collection\Validator\Chain\Config;

use LDL\Framework\Base\Exception\LockingException;
use LDL\Framework\Base\Traits\LockableObjectInterfaceTrait;
use LDL\Validators\Collection\Interfaces\CollectionInterface;
use LDL\Validators\Collection\Traits\CollectionTrait;
use LDL\Validators\Collection\Traits\Validator\ValueValidatorChainTrait;
use LDL\Validators\Collection\Types\Scalar\Validator\ScalarValidator;
use LDL\Validators\Collection\Validator\Chain\Config\Item\ValidatorChainConfigItem;
use LDL\Validators\Collection\Validator\Chain\Config\Item\ValidatorChainConfigItemInterface;
use LDL\Validators\Exception\TypeMismatchException;

class ValidatorChainConfig implements ValidatorChainConfigInterface
{
    use CollectionTrait;
    use LockableObjectInterfaceTrait;
    use ValueValidatorChainTrait;

    public static function fromArray(array $array)
    {
        $self = new self;

        foreach($array as $configItem){
            $self->append(ValidatorChainConfigItem::fromArray($configItem));
        }

        return $self;
    }

    public function toArray(): array
    {
        $return = [];

        /**
         * @var ValidatorChainConfigItem $item
         */
        foreach($this->items as $key => $item){
            $return[] = $item->toArray();
        }

        return $return;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Append an element to the collection
     *
     * @param mixed $item
     * @param mixed $key
     *
     * @throws \Exception
     *
     * @return CollectionInterface
     */
    public function append($item, $key=null) : CollectionInterface
    {
        if($this->isLocked()){
            throw new LockingException(sprintf('Can not call %s on a locked collection', __METHOD__));
        }

        $key = $key ?? $this->count;

        $this->validateKey($key);
        $this->validateItem($item);

        $this->last = $key;

        if(null === $this->first){
            $this->first = $key;
        }

        $this->items[$key] = $item;
        $this->count++;

        return $this;
    }

    public function replace($item, $key) : CollectionInterface
    {
        if(!$this->offsetExists($key)){
            return $this->append($item, $key);
        }

        if($this->isLocked()){
            throw new LockingException(sprintf('Can not call %s on a locked collection', __METHOD__));
        }

        $this->validateKey($key);
        $this->validateItem($item);

        $this->items[$key] = $item;

        return $this;
    }

    public function remove($key): CollectionInterface
    {
        if($this->isLocked()){
            throw new LockingException(sprintf('Can not call %s on a locked collection', __METHOD__));
        }

        $this->offsetGet($key);
        unset($this->items[$key]);
        return $this;
    }

    private function validateKey($key) : void
    {
        (new ScalarValidator($strict = true, $acceptToStringObjects = true))
            ->validateValue($this, $key,null);
    }

    private function validateItem($item) : void
    {
        if($this->isLocked()){
            $msg  = 'Validator chain is locked, no additional validators can be added';
            throw new LockingException($msg);
        }

        if(!is_object($item)){
            $msg = sprintf(
                '"%s" expects an object, "%s" was given',
                __CLASS__,
                gettype($item)
            );

            throw new TypeMismatchException($msg);
        }

        if($item instanceof ValidatorChainConfigItemInterface){
            return;
        }

        $msg = sprintf(
            '"%s" expects an object which implements "%s", but "%s" was given',
            __CLASS__,
            ValidatorChainConfigItemInterface::class,
            get_class($item)
        );

        throw new TypeMismatchException($msg);
    }

}