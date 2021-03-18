<?php declare(strict_types=1);

namespace LDL\Type\Collection\Types\Object\Validator;

use LDL\Type\Collection\Types\Object\Validator\Config\ClassComplianceValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Exception\TypeMismatchException;
use LDL\Validators\ValidatorInterface;

class ClassComplianceValidator implements ValidatorInterface
{
    /**
     * @var ClassComplianceValidatorConfig
     */
    private $config;

    public function __construct(string $class, bool $strict=true)
    {
        $this->config = new ClassComplianceValidatorConfig($class, $strict);
    }

    public function validate($item): void
    {
        if(!is_object($item)){
            $msg = sprintf(
                'Validator "%s", only accepts objects as items being part of a collection',
                __CLASS__
            );
            throw new TypeMismatchException($msg);
        }

        $class = $this->config->getClass();

        if($item instanceof $class) {
            return;
        }

        $msg = sprintf(
            'Item to be added of class "%s", is not an instanceof class: "%s"',
            get_class($item),
            $class
        );

        throw new TypeMismatchException($msg);
    }


    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface
    {
        if(false === $config instanceof ClassComplianceValidatorConfig){
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
     * @return ClassComplianceValidatorConfig
     */
    public function getConfig(): ClassComplianceValidatorConfig
    {
        return $this->config;
    }
}