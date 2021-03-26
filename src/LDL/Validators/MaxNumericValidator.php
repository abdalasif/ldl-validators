<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\Exception\InvalidConfigException;
use LDL\Validators\Config\NumericValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Exception\NumericRangeValidatorException;

class MaxNumericValidator implements ValidatorInterface, HasValidatorConfigInterface
{
    /**
     * @var NumericValidatorConfig
     */
    private $config;

    public function __construct($value, bool $strict=true)
    {
        $this->config = new NumericValidatorConfig($value, $strict);
    }

    /**
     * @param mixed $value
     * @throws NumericRangeValidatorException
     */
    public function validate($value): void
    {
        if($value <= $this->config->getValue()){
            return;
        }

        $msg = "Value can not be greater than: {$this->config->getValue()}";
        throw new NumericRangeValidatorException($msg);
    }

    /**
     * @param ValidatorConfigInterface $config
     * @return MaxNumericValidator
     * @throws InvalidConfigException
     */
    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface
    {
        if(false === $config instanceof NumericValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new InvalidConfigException($msg);
        }

        /**
         * @var NumericValidatorConfig $config
         */
        return new self($config->getValue(), $config->isStrict());
    }

    /**
     * @return NumericValidatorConfig
     */
    public function getConfig(): NumericValidatorConfig
    {
        return $this->config;
    }
}
