<?php declare(strict_types=1);

namespace LDL\Validators\Collection\Validator;

use LDL\Validators\Config\MinNumericValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Exception\NumericRangeValidatorException;
use LDL\Validators\ValidatorInterface;

class MinNumericValidator implements ValidatorInterface
{
    /**
     * @var MinNumericValidatorConfig
     */
    private $config;

    public function __construct($value, bool $strict=true)
    {
        $this->config = new MinNumericValidatorConfig($value, $strict);
    }

    public function validate($value): void
    {
        if($value >= $this->config->getValue()){
            return;
        }

        $msg = "Value can not be less than: {$this->config->getValue()}";
        throw new NumericRangeValidatorException($msg);
    }

    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface
    {
        if(false === $config instanceof MinNumericValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new Exception\InvalidConfigException($msg);
        }

        /**
         * @var MinNumericValidatorConfig $config
         */
        return new self($config->getValue(), $config->isStrict());
    }

    /**
     * @return MinNumericValidatorConfig
     */
    public function getConfig(): MinNumericValidatorConfig
    {
        return $this->config;
    }
}
