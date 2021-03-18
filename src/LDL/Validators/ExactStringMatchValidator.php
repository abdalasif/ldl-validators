<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\Exception\InvalidConfigException;
use LDL\Validators\Config\ExactStringMatchValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;

class ExactStringMatchValidator implements ValidatorInterface
{
    /**
     * @var ExactStringMatchValidatorConfig
     */
    private $config;

    public function __construct(string $name, bool $strict=true)
    {
        $this->config = new ExactStringMatchValidatorConfig($name, $strict);
    }

    /**
     * @param mixed $value
     */
    public function validate($value): void
    {
        if($value === $this->config->getValue()){
            return;
        }

        throw new \LogicException('No match');
    }

    /**
     * @param ValidatorConfigInterface $config
     * @return ExactStringMatchValidator
     * @throws InvalidConfigException
     */
    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface
    {
        if(false === $config instanceof ExactStringMatchValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new InvalidConfigException($msg);
        }

        /**
         * @var ExactStringMatchValidatorConfig $config
         */
        return new self($config->getValue(), $config->isStrict());
    }

    /**
     * @return ExactStringMatchValidatorConfig
     */
    public function getConfig(): ExactStringMatchValidatorConfig
    {
        return $this->config;
    }
}