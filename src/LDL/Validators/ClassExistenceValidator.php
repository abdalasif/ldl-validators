<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\BasicValidatorConfig;
use LDL\Validators\Config\Exception\InvalidConfigException;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Exception\TypeMismatchException;

class ClassExistenceValidator implements ValidatorInterface, HasValidatorConfigInterface
{
    /**
     * @var BasicValidatorConfig
     */
    private $config;

    public function __construct(bool $strict=true)
    {
        $this->config = BasicValidatorConfig::fromArray([
            'strict' => $strict
        ]);
    }

    /**
     * @param mixed $value
     * @throws TypeMismatchException
     */
    public function validate($value): void
    {
        if(class_exists($value)){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must be a string representing an existing class, "%s" was given. Perhaps an autoloader is missing? Perhaps the namespace of the class is written incorrectly?',
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
        if(false === $config instanceof ClassExistenceValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new InvalidConfigException($msg);
        }

        /**
         * @var ClassExistenceValidatorConfig $config
         */
        return new self($config->isStrict());
    }

    /**
     * @return ClassExistenceValidatorConfig
     */
    public function getConfig(): ClassExistenceValidatorConfig
    {
        return $this->config;
    }
}