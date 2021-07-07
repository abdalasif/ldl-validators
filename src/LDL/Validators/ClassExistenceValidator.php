<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Exception\TypeMismatchException;
use LDL\Validators\Traits\ValidatorHasConfigInterfaceTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class ClassExistenceValidator implements ValidatorInterface, NegatedValidatorInterface, ValidatorHasConfigInterface
{
    use ValidatorValidateTrait;
    use ValidatorHasConfigInterfaceTrait;

    public function __construct(bool $negated=false, bool $dumpable=true, string $description=null)
    {
        $this->_tConfig = new Config\BasicValidatorConfig($negated, $dumpable, $description);
    }

    public function assertTrue($value): void
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
     * Might sound awkward, but who am I to state anything about use cases ¯\_(ツ)_/¯
     *
     * @TODO Perhaps format validation for the parameter would be necessary ?
     *
     * @param $value
     * @throws TypeMismatchException
     */
    public function assertFalse($value): void
    {
        if(!class_exists($value)){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must be a string representing a NON existing class, "%s" was given.',
            __CLASS__,
            gettype($value)
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
}