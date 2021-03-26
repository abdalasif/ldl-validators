<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\Exception\InvalidConfigException;
use LDL\Validators\Config\ObjectValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Exception\TypeMismatchException;

class ObjectValidator implements ValidatorInterface, HasValidatorConfigInterface
{
    /**
     * @var ObjectValidatorConfig
     */
    private $config;

    public function __construct(bool $strict=true)
    {
        $this->config = new ObjectValidatorConfig($strict);
    }

    /**
     * @param mixed $value
     * @throws TypeMismatchException
     */
    public function validate($value) : void
    {
        if(is_object($value)){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must be an Object, %s was given',
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
        if(false === $config instanceof ObjectValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new InvalidConfigException($msg);
        }

        /**
         * @var ObjectValidatorConfig $config
         */
        return new self($config->isStrict());
    }

    /**
     * @return ObjectValidatorConfig
     */
    public function getConfig(): ObjectValidatorConfig
    {
        return $this->config;
    }
}