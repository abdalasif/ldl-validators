<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\Exception\InvalidConfigException;
use LDL\Validators\Config\ExactFileNameValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;

class ExactFileNameValidator implements ValidatorInterface
{
    /**
     * @var ExactFileNameValidatorConfig
     */
    private $config;

    public function __construct(string $name, bool $strict=true)
    {
        $this->config = new ExactFileNameValidatorConfig($name, $strict);
    }

    /**
     * @param mixed $value
     */
    public function validate($value): void
    {
        if($value === $this->config->getName()){
            return;
        }

        throw new \LogicException('No match');
    }

    /**
     * @param ValidatorConfigInterface $config
     * @return ExactFileNameValidator
     * @throws InvalidConfigException
     */
    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface
    {
        if(false === $config instanceof ExactFileNameValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new InvalidConfigException($msg);
        }

        /**
         * @var ExactFileNameValidatorConfig $config
         */
        return new self($config->getName(), $config->isStrict());
    }

    /**
     * @return ExactFileNameValidatorConfig
     */
    public function getConfig(): ExactFileNameValidatorConfig
    {
        return $this->config;
    }
}