<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\Exception\InvalidConfigException;
use LDL\Validators\Config\NumberValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Exception\TypeMismatchException;

class NumberValidator implements ValidatorInterface, HasValidatorConfigInterface
{
    /**
     * @var NumberValidatorConfig
     */
    private $config;

    public function __construct(bool $strict=true)
    {
        $this->config = new NumberValidatorConfig($strict);
    }

    /**
     * @param mixed $value
     * @throws TypeMismatchException
     */
    public function validate($value): void
    {
        if(is_numeric($value)){
            return;
        }

        $msg = sprintf(
          'Value expected for "%s", must comply to is_numeric function, "%s" given',
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
        if(false === $config instanceof NumberValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new InvalidConfigException($msg);
        }

        /**
         * @var NumberValidatorConfig $config
         */
        return new self($config->isStrict());
    }

    /**
     * @return NumberValidatorConfig
     */
    public function getConfig(): NumberValidatorConfig
    {
        return $this->config;
    }
}