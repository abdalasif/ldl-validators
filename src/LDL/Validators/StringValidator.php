<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\Exception\InvalidConfigException;
use LDL\Validators\Config\StringValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Exception\TypeMismatchException;

class StringValidator implements ValidatorInterface, HasValidatorConfigInterface
{
    /**
     * @var StringValidatorConfig
     */
    private $config;

    public function __construct(bool $strict=true)
    {
        $this->config = new StringValidatorConfig($strict);
    }

    /**
     * @param mixed $value
     * @throws TypeMismatchException
     */
    public function validate($value): void
    {
        if(is_string($value)){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must be of type string, "%s" was given',
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
        if(false === $config instanceof StringValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new InvalidConfigException($msg);
        }

        /**
         * @var StringValidatorConfig $config
         */
        return new self($config->isStrict());
    }

    /**
     * @return StringValidatorConfig
     */
    public function getConfig(): StringValidatorConfig
    {
        return $this->config;
    }
}