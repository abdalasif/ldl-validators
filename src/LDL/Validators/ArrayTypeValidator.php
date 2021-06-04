<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Exception\TypeMismatchException;
use LDL\Validators\Traits\ValidatorValidateTrait;

class ArrayTypeValidator implements ValidatorInterface, NegatedValidatorInterface
{
    use ValidatorValidateTrait;

    /**
     * @var Config\BasicValidatorConfig
     */
    private $config;

    public function __construct(
        bool $negated = false,
        bool $dumpable=true,
        string $description=null
    )
    {
        $this->config = new Config\BasicValidatorConfig($negated, $dumpable, $description);
    }

    public function assertTrue($value): void
    {
        if(is_array($value)){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must be of type array, "%s" was given',
            __CLASS__,
            gettype($value)
        );

        throw new TypeMismatchException($msg);
    }

    public function assertFalse($value): void
    {
        if(!is_array($value)){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must NOT be of type array, "%s" was given',
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

    /**
     * @return Config\BasicValidatorConfig
     */
    public function getConfig(): Config\BasicValidatorConfig
    {
        return $this->config;
    }
}