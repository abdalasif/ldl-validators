<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\ClassComplianceValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Exception\TypeMismatchException;
use LDL\Validators\Traits\ValidatorDescriptionTrait;
use LDL\Validators\Traits\ValidatorHasConfigInterfaceTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class ClassComplianceValidator implements ValidatorInterface, NegatedValidatorInterface, ValidatorHasConfigInterface
{
    use ValidatorValidateTrait {validate as _validate;}
    use ValidatorHasConfigInterfaceTrait;
    use ValidatorDescriptionTrait;

    private const DESCRIPTION = 'Validate class';

    public function __construct(
        string $class,
        bool $strict=false,
        bool $negated=false,
        bool $dumpable=true,
        string $description = null
    )
    {
        $this->_tConfig = new ClassComplianceValidatorConfig($class, $strict, $negated, $dumpable);
        $this->_tDescription = $description ?? self::DESCRIPTION;
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

        $this->_validate($value);
    }

    public function assertTrue($value) : void
    {
        if($this->compare($value)){
            return;
        }

        $msg = sprintf(
            'Value of class "%s", does not complies to class: "%s"',
            get_class($value),
            $this->_tConfig->getClass()
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
            $this->_tConfig->getClass()
        );

        throw new TypeMismatchException($msg);
    }

    private function compare($value)
    {
        $class = $this->_tConfig->getClass();
        return $this->_tConfig->isStrict() ? get_class($value) === $class : $value instanceof $class;
    }

    /**
     * @param ValidatorConfigInterface $config
     * @param string|null $description
     * @return ValidatorInterface
     * @throws TypeMismatchException
     */
    public static function fromConfig(ValidatorConfigInterface $config, string $description=null): ValidatorInterface
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
        return new self(
            $config->getClass(),
            $config->isStrict(),
            $config->isNegated(),
            $config->isDumpable(),
            $description
        );
    }
}