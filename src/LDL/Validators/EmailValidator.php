<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\BasicValidatorConfig;
use LDL\Validators\Config\ClassComplianceValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Exception\TypeMismatchException;

class EmailValidator implements ValidatorInterface, HasValidatorConfigInterface
{
    /**
     * @var BasicValidatorConfig
     */
    private $config;

    public function __construct(string $class, bool $strict=true)
    {
        $this->config = new BasicValidatorConfig($strict);
    }

    /**
     * @param mixed $value
     * @throws TypeMismatchException
     */
    public function validate($value): void
    {
        if(filter_var($value, \FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must be a valid email',
            __CLASS__
        );

        throw new TypeMismatchException($msg);
    }

    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface
    {
        if(false === $config instanceof BasicValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );

            throw new TypeMismatchException($msg);
        }

        /**
         * @var ClassComplianceValidatorConfig $config
         */
        return new self($config->getClass(), $config->isStrict());
    }

    /**
     * @return BasicValidatorConfig
     */
    public function getConfig(): BasicValidatorConfig
    {
        return $this->config;
    }
}