<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\InterfaceComplianceValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Exception\TypeMismatchException;

class InterfaceComplianceValidator implements ValidatorInterface, HasValidatorConfigInterface
{
    /**
     * @var InterfaceComplianceValidatorConfig
     */
    private $config;

    public function __construct(string $interface, bool $strict=true)
    {
        $this->config = new InterfaceComplianceValidatorConfig($interface, $strict);
    }

    public function validate($value) : void
    {
        if(!is_object($value)){
            $msg = sprintf(
                'Validator "%s", only accepts objects as items being part of a collection',
                __CLASS__
            );
            throw new TypeMismatchException($msg);
        }

        $interface = $this->config->getInterface();

        if($value instanceof $interface) {
            return;
        }

        $msg = sprintf(
            'Item to be added of class "%s", does not complies to interface: "%s"',
            get_class($value),
            $interface
        );

        throw new TypeMismatchException($msg);
    }

    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface
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
        return new self($config->getInterface(), $config->isStrict());
    }

    /**
     * @return InterfaceComplianceValidatorConfig
     */
    public function getConfig(): InterfaceComplianceValidatorConfig
    {
        return $this->config;
    }
}