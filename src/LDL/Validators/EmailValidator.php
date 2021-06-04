<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Exception\TypeMismatchException;
use LDL\Validators\Traits\ValidatorValidateTrait;

class EmailValidator implements ValidatorInterface, NegatedValidatorInterface
{
    use ValidatorValidateTrait;

    /**
     * @var Config\BasicValidatorConfig
     */
    private $config;

    public function __construct(bool $negated=false, bool $dumpable=true, string $description=null)
    {
        $this->config = new Config\BasicValidatorConfig($negated, $dumpable, $description);
    }

    public function assertTrue($value): void
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

    public function assertFalse($value): void
    {
        if(!filter_var($value, \FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", can not be an email address',
            __CLASS__
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