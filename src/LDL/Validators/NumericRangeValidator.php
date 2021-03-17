<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\Exception\InvalidConfigException;
use LDL\Validators\Config\NumericRangeValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;

class NumericRangeValidator implements ValidatorInterface
{
    /**
     * @var NumericRangeValidatorConfig
     */
    private $config;

    public function __construct($min, $max, bool $strict = true)
    {
        $this->config = new NumericRangeValidatorConfig($min, $max, $strict);
    }

    public function validate($value): void
    {
        $this->config->getMin()->validate($value);
        $this->config->getMax()->validate($value);
    }

    /**
     * @param ValidatorConfigInterface $config
     * @return ValidatorInterface
     * @throws InvalidConfigException
     */
    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface
    {
        if(false === $config instanceof NumericRangeValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new InvalidConfigException($msg);
        }

        /**
         * @var NumericRangeValidatorConfig $config
         */
        return new self(
            $config->getMin()->getConfig()->getValue(),
            $config->getMax()->getConfig()->getValue(),
            $config->isStrict()
        );
    }

    /**
     * @return ValidatorConfigInterface
     */
    public function getConfig(): ValidatorConfigInterface
    {
        return $this->config;
    }
}
