<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\ClassComplianceValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Exception\TypeMismatchException;

class ClassComplianceValidator implements ValidatorInterface
{
    /**
     * @var ClassComplianceValidatorConfig
     */
    private $config;

    public function __construct(
        string $class,
        bool $strict=false,
        bool $negated=false,
        bool $dumpable=true
    )
    {
        $this->config = new ClassComplianceValidatorConfig($class, $strict, $negated, $dumpable);
    }

    /**
     * @param mixed $value
     * @throws TypeMismatchException
     */
    public function validate($value): void
    {
        if(!is_object($value)){
            $msg = sprintf(
                'Value expected for "%s", must be an Object, "%s" was given',
                __CLASS__,
                gettype($value)
            );
            throw new TypeMismatchException($msg);
        }

        $this->config->isNegated() ? $this->assertFalse($value) : $this->assertTrue($value);
    }

    public function assertTrue($value) : void
    {
        if($this->compare($value)){
            return;
        }

        $msg = sprintf(
            'Value of class "%s", does not complies to class: "%s"',
            get_class($value),
            $this->config->getClass()
        );

        throw new TypeMismatchException($msg);
    }

    public function assertFalse($value) : void
    {
        if(!$this->compare($value)){
            return;
        }

        $msg = sprintf(
            'Value of class "%s", can NOT be of class: "%s"',
            get_class($value),
            $this->config->getClass()
        );

        throw new TypeMismatchException($msg);
    }

    private function compare($value)
    {
        $class = $this->config->getClass();
        return $this->config->isStrict() ? get_class($value) === $class : is_subclass_of($value, $class);
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
        return new self($config->getClass(), $config->isStrict(), $config->isNegated(), $config->isDumpable());
    }

    /**
     * @return ClassComplianceValidatorConfig
     */
    public function getConfig(): ClassComplianceValidatorConfig
    {
        return $this->config;
    }
}