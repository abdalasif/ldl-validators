<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Exception\TypeMismatchException;

class NumericValidator implements ValidatorInterface
{
    /**
     * @var Config\BasicValidatorConfig
     */
    private $config;

    public function __construct(bool $negated=false, bool $dumpable=true)
    {
        $this->config = new Config\BasicValidatorConfig($negated);
    }

    public function validate($value): void
    {
        $this->config->isNegated() ? $this->assertFalse($value) : $this->assertTrue($value);
    }

    public function assertTrue($value): void
    {
        if(is_numeric($value)){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must be of type numeric; "%s" was given',
            __CLASS__,
            is_scalar($value) ? var_export($value, true) : gettype($value)
        );

        throw new TypeMismatchException($msg);
    }

    public function assertFalse($value): void
    {
        if(!is_numeric($value)){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must not be numeric; "%s" was given',
            __CLASS__,
            is_scalar($value) ? var_export($value, true) : gettype($value)
        );

        throw new TypeMismatchException($msg);
    }


    /**
     * @param Config\ValidatorConfigInterface $config
     * @return ValidatorInterface
     * @throws \InvalidArgumentException
     */
    public static function fromConfig(Config\ValidatorConfigInterface $config): ValidatorInterface
    {
        if(false === $config instanceof Config\BasicValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new \InvalidArgumentException($msg);
        }

        /**
         * @var Config\ValidatorConfigInterface $config
         */
        return new self($config->isNegated(), $config->isDumpable());
    }

    /**
     * @return Config\BasicValidatorConfig
     */
    public function getConfig(): Config\BasicValidatorConfig
    {
        return $this->config;
    }
}