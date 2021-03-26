<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\Exception\InvalidConfigException;
use LDL\Validators\Config\IntegerValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Exception\TypeMismatchException;

class IntegerValidator implements ValidatorInterface, HasValidatorConfigInterface
{
    /**
     * @var IntegerValidatorConfig
     */
    private $config;

    public function __construct(bool $strict=true)
    {
        $this->config = new IntegerValidatorConfig($strict);
    }

    /**
     * @param mixed $value
     * @throws TypeMismatchException
     */
    public function validate($value): void
    {
        if(is_int($value)){
            return;
        }

        $msg = sprintf(
          'Value expected for "%s", must be of type integer, "%s" was given',
            __CLASS__,
            gettype($value)
        );

        throw new TypeMismatchException($msg);
    }

    /**
     * @param ValidatorConfigInterface $config
     * @return ValidatorInterface
     * @throws InvalidConfigException
     */
    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface
    {
        if(false === $config instanceof IntegerValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new InvalidConfigException($msg);
        }

        /**
         * @var IntegerValidatorConfig $config
         */
        return new self($config->isStrict());
    }

    /**
     * @return IntegerValidatorConfig
     */
    public function getConfig(): IntegerValidatorConfig
    {
        return $this->config;
    }
}