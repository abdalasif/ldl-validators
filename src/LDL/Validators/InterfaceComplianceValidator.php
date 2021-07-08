<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\InterfaceComplianceValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Exception\TypeMismatchException;
use LDL\Validators\Traits\ValidatorDescriptionTrait;
use LDL\Validators\Traits\ValidatorHasConfigInterfaceTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class InterfaceComplianceValidator implements ValidatorInterface, NegatedValidatorInterface, ValidatorHasConfigInterface
{
    use ValidatorValidateTrait {validate as _validate;}
    use ValidatorHasConfigInterfaceTrait;
    use ValidatorDescriptionTrait;

    private const DESCRIPTION = 'Validate interface';

    public function __construct(string $interface, bool $negated=false, bool $dumpable=true, string $description=null)
    {
        $this->_tConfig = new InterfaceComplianceValidatorConfig($interface, $negated, $dumpable);
        $this->_tDescription = $description ?? self::DESCRIPTION;
    }

    /**
     * @param mixed $value
     * @throws TypeMismatchException
     */
    public function validate($value) : void
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

    public function assertTrue($value): void
    {
        $interface = $this->_tConfig->getInterface();

        if($value instanceof $interface) {
            return;
        }

        $msg = sprintf(
            'Value of class "%s", does not complies to interface: "%s"',
            get_class($value),
            $interface
        );

        throw new TypeMismatchException($msg);
    }

    public function assertFalse($value): void
    {
        $interface = $this->_tConfig->getInterface();

        if(!$value instanceof $interface) {
            return;
        }

        $msg = sprintf(
            'Value of class "%s", must NOT comply to interface: "%s"',
            get_class($value),
            $interface
        );

        throw new TypeMismatchException($msg);
    }

    /**
     * @param ValidatorConfigInterface $config
     * @param string|null $description
     * @return ValidatorInterface
     * @throws TypeMismatchException
     */
    public static function fromConfig(ValidatorConfigInterface $config, string $description=null): ValidatorInterface
    {
        if(false === $config instanceof InterfaceComplianceValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new TypeMismatchException($msg);
        }

        /**
         * @var InterfaceComplianceValidatorConfig $config
         */
        return new self($config->getInterface(), $config->isNegated(), $config->isDumpable(), $description);
    }
}