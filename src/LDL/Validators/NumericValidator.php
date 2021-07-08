<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Exception\TypeMismatchException;
use LDL\Validators\Traits\ValidatorDescriptionTrait;
use LDL\Validators\Traits\ValidatorHasConfigInterfaceTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class NumericValidator implements ValidatorInterface, NegatedValidatorInterface, ValidatorHasConfigInterface
{
    use ValidatorValidateTrait;
    use ValidatorHasConfigInterfaceTrait;
    use ValidatorDescriptionTrait;

    private const DESCRIPTION = 'Validate numeric';

    public function __construct(bool $negated=false, bool $dumpable=true, string $description=null)
    {
        $this->_tConfig = new Config\BasicValidatorConfig($negated, $dumpable);
        $this->_tDescription = $description ?? self::DESCRIPTION;
    }

    public function assertTrue($value): void
    {
        if(is_numeric($value)){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must be of type numeric; "%s" was given',
            __CLASS__,
            is_scalar($value) ? var_export($value, true) : gettype($value)
        );

        throw new TypeMismatchException($msg);
    }

    public function assertFalse($value): void
    {
        if(!is_numeric($value)){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must not be numeric; "%s" was given',
            __CLASS__,
            is_scalar($value) ? var_export($value, true) : gettype($value)
        );

        throw new TypeMismatchException($msg);
    }


    /**
     * @param Config\ValidatorConfigInterface $config
     * @param string|null $description
     * @return ValidatorInterface
     * @throws \InvalidArgumentException
     */
    public static function fromConfig(Config\ValidatorConfigInterface $config, string $description=null): ValidatorInterface
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
        return new self($config->isNegated(), $config->isDumpable(), $description);
    }
}