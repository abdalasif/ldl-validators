<?php declare(strict_types=1);

/**
 * This type of validation is a combined validation, basically it's a collection of different validators
 * which are validated in a chain like fashion.
 *
 * This collection also implements the FilterByInterface interface, which makes it possible to filter the validators
 * appended in this collection.
 */

namespace LDL\Validators\Collection\Validator\Chain\Traits;

use LDL\Framework\Base\Traits\LockableObjectInterfaceTrait;
use LDL\Validators\Collection\Interfaces\CollectionInterface;
use LDL\Validators\Collection\Interfaces\Validation\KeyValidatorInterface;
use LDL\Validators\Collection\Interfaces\Validation\ValidatorInterface;
use LDL\Validators\Collection\Interfaces\Validation\ValueValidatorInterface;
use LDL\Validators\Collection\Traits\Filter\FilterByInterfaceTrait;
use LDL\Validators\Collection\Traits\CollectionTrait;
use LDL\Validators\Collection\Types\Scalar\Validator\ScalarValidator;
use LDL\Validators\Collection\Validator\Chain\Config\Item\ValidatorChainConfigItem;
use LDL\Validators\Collection\Validator\Chain\Config\Item\ValidatorChainConfigItemInterface;
use LDL\Validators\Collection\Validator\Chain\Config\ValidatorChainConfig;
use LDL\Validators\Collection\Validator\Chain\Config\ValidatorChainConfigInterface;
use LDL\Validators\Collection\Validator\Exception\ValidatorChainSoftValidationException;
use LDL\Validators\Collection\Validator\ValidatorChainInterface;
use LDL\Validators\Exception\TypeMismatchException;
use LDL\Framework\Base\Exception\LockingException;

trait ValidatorChainTrait
{
    use CollectionTrait;
    use FilterByInterfaceTrait;
    use LockableObjectInterfaceTrait;

    /**
     * @var string
     */
    private $class;

    /**
     * @var ValidatorChainConfigInterface
     */
    private $config;

    public function init(iterable $items=null, string $class=null) : void
    {
        $this->config = new ValidatorChainConfig();
        $this->class = $class;

        if(null !== $items){
            $this->appendMany($items);
        }
    }

    public function getConfig(): ValidatorChainConfigInterface
    {
        return $this->config;
    }

    public static function fromConfig(ValidatorChainConfigInterface $config): ValidatorChainInterface
    {
        $instance = new self();

        /**
         * @var ValidatorChainConfigItemInterface $validator
         */
        foreach($config as $validator){
            $class = $validator->getClass();
            $instance->append($class::fromArray($validator->getConfig()), $validator->getKey());
        }

        return $instance;
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

    public function validate(CollectionInterface $collection, $item, $key) : void
    {
        if(0 === $this->count){
            return;
        }

        /**
         * @var \Exception[]
         */
        $exceptions = [];
        $atLeastOneValid = false;

        /**
         * @var ValueValidatorInterface $validator
         */
        foreach($this as $validator){

            $method = $this->class === KeyValidatorInterface::class ? 'validateKey' : 'validateValue';

            if($validator->getConfig()->isStrict()){
                $validator->$method($collection, $item, $key);
                $atLeastOneValid=true;
                continue;
            }

            try{
                $validator->$method($collection, $item, $key);
                $atLeastOneValid = true;
            }catch(\Exception $e){
                $exceptions[] = $e;
            }
        }

        if($atLeastOneValid){
            return;
        }

        $messages = [];

        foreach($exceptions as $exception){
            $msg = sprintf('"%s": "%s"', get_class($exception), $exception->getMessage());
            $messages[] = $msg;
        }

        throw new ValidatorChainSoftValidationException(implode("\n", $messages));
    }

    public function appendMany(iterable $items, bool $useKey = false, bool $addToConfig=true): CollectionInterface
    {
        foreach ($items as $key => $value) {
            $this->append($value, $useKey ? $key : null, $addToConfig);
        }

        return $this;
    }

    /**
     * @param ValidatorInterface $item
     * @param null $key
     * @param bool $addToConfig
     * @return CollectionInterface
     * @throws LockingException
     * @throws TypeMismatchException
     * @throws \LDL\Framework\Base\Exception\ArrayFactoryException
     * @throws \LDL\Framework\Base\Exception\ToArrayException
     */
    public function append($item, $key=null, bool $addToConfig=true) : CollectionInterface
    {
        if($this->isLocked()){
            throw new LockingException(sprintf('Can not call %s on a locked collection', __METHOD__));
        }

        $key = $key ?? $this->count;
        $this->validateKey($key);
        $this->validateItem($item);

        $this->last = $key;

        if($addToConfig) {
            $this->config->append(
                ValidatorChainConfigItem::fromArray([
                    'key' => $key,
                    'validator' => get_class($item),
                    'config' => [
                        'class' => get_class($item->getConfig()),
                        'config' => $item->getConfig()->toArray()
                    ]
                ]), $key
            );
        }

        if(null === $this->first){
            $this->first = $key;
        }

        $this->items[$key] = $item;
        $this->count++;

        return $this;
    }

    private function validateItem($item) : void
    {
        if(!is_object($item)){
            $msg = sprintf(
                '"%s" expects an object, "%s" was given',
                __CLASS__,
                gettype($item)
            );

            throw new TypeMismatchException($msg);
        }

        if($item instanceof $this->class){
            return;
        }

        $msg = sprintf(
            '"%s" expects an object which implements "%s", but "%s" was given',
            get_class($this),
            $this->class,
            get_class($item)
        );

        throw new TypeMismatchException($msg);
    }

    private function validateKey($key) : void
    {
        (new ScalarValidator($strict = true, $acceptToStringObjects = true))
            ->validateValue($this, $key,null);
    }

    public function jsonSerialize()
    {
        return $this->getConfig();
    }
}
