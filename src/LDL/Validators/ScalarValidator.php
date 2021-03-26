<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\Exception\InvalidConfigException;
use LDL\Validators\Config\ScalarValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Exception\TypeMismatchException;

class ScalarValidator implements ValidatorInterface, HasValidatorConfigInterface
{
    /**
     * @var ScalarValidatorConfig
     */
    private $config;

    public function __construct(bool $acceptToStringObjects=true, bool $strict = false)
    {
        $this->config = new ScalarValidatorConfig($acceptToStringObjects, $strict);
    }

    /**
     * @param mixed $value
     * @throws TypeMismatchException
     */
    public function validate($value): void
    {
        if(is_scalar($value)){
            return;
        }

        /**
         * Object with __toString method
         */
        if(
            $this->config->isAcceptToStringObjects() &&
            is_object($value) &&
            in_array('__tostring', array_map('strtolower', get_class_methods($value)), true)
        ){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must be of type scalar, "%s" was given',
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
        if(false === $config instanceof ScalarValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new InvalidConfigException($msg);
        }

        /**
         * @var ScalarValidatorConfig $config
         */
        return new self($config->isAcceptToStringObjects(), $config->isStrict());
    }

    /**
     * @return ScalarValidatorConfig
     */
    public function getConfig(): ScalarValidatorConfig
    {
        return $this->config;
    }
}