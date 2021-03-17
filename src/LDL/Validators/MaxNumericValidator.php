<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\MaxNumericValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Exception\NumericRangeValidatorException;

class MaxNumericValidator implements ValidatorInterface
{
    /**
     * @var MaxNumericValidatorConfig
     */
    private $config;

    public function __construct($value, bool $strict=true)
    {
        $this->config = new MaxNumericValidatorConfig($value, $strict);
    }

    public function validate($value): void
    {
        if($value <= $this->config->getValue()){
            return;
        }

        $msg = "Items in this collection can not be greater than: {$this->config->getValue()}";
        throw new NumericRangeValidatorException($msg);
    }

    /**
     * @param ValidatorConfigInterface $config
     * @return ValidatorInterface
     * @throws Exception\InvalidConfigException
     */
    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface
    {
        if(false === $config instanceof MaxNumericValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new Exception\InvalidConfigException($msg);
        }

        /**
         * @var MaxNumericValidatorConfig $config
         */
        return new self($config->getValue(), $config->isStrict());
    }

    /**
     * @return MaxNumericValidatorConfig
     */
    public function getConfig(): MaxNumericValidatorConfig
    {
        return $this->config;
    }
}
