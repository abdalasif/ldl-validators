<?php declare(strict_types=1);

namespace LDL\Validators\Collection\Validator;

use LDL\Validators\Collection\Interfaces\CollectionInterface;
use LDL\Validators\Collection\Interfaces\Validation\AppendItemValidatorInterface;
use LDL\Validators\Collection\Interfaces\Validation\KeyValidatorInterface;
use LDL\Validators\Collection\Interfaces\Validation\ValidatorInterface;
use LDL\Validators\Collection\Interfaces\Validation\ValueValidatorInterface;
use LDL\Validators\Collection\Traits\Validator\ValidatorInterfaceTrait;
use LDL\Validators\Collection\Validator\Config\NumericRangeValidatorConfig;
use LDL\Validators\Collection\Validator\Config\ValidatorConfigInterface;

class NumericRangeValidator implements AppendItemValidatorInterface, ValueValidatorInterface, KeyValidatorInterface
{
    use ValidatorInterfaceTrait;

    /**
     * @var NumericRangeValidatorConfig
     */
    private $config;

    public function __construct($min, $max, bool $strict = true)
    {
        $this->config = new NumericRangeValidatorConfig($min, $max, $strict);
    }

    public function validateKey(CollectionInterface $collection, $item, $key): void
    {
        $this->config->getMin()->validateKey($collection, $item, $key);
        $this->config->getMax()->validateKey($collection, $item, $key);
    }

    /**
     * @param CollectionInterface $collection
     * @param mixed $item
     * @param number|string $key
     * @throws Exception\NumericRangeValidatorException
     */
    public function validateValue(CollectionInterface $collection, $item, $key): void
    {
        $this->config->getMin()->validateValue($collection, $item, $key);
        $this->config->getMax()->validateValue($collection, $item, $key);
    }

    /**
     * @param ValidatorConfigInterface $config
     * @return ValidatorInterface
     * @throws Exception\InvalidConfigException
     */
    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface
    {
        if(false === $config instanceof NumericRangeValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new Exception\InvalidConfigException($msg);
        }

        /**
         * @var NumericRangeValidatorConfig $config
         */
        return new self($config->getMin(), $config->getMax(), $config->isStrict());
    }

    /**
     * @return NumericRangeValidatorConfig
     */
    public function getConfig(): NumericRangeValidatorConfig
    {
        return $this->config;
    }
}
