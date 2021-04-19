<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\Exception\InvalidConfigException;
use LDL\Validators\Config\StringEqualsValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;

class StringEqualsValidator implements ValidatorInterface
{
    /**
     * @var StringEqualsValidatorConfig
     */
    private $config;

    public function __construct(string $name, bool $strict=true, bool $negated=false, bool $dumpable=true)
    {
        $this->config = new StringEqualsValidatorConfig($name, $strict, $negated, $dumpable);
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

    public function assertTrue($value): void
    {
        $comparison = $this->config->isStrict() ? $this->config->getValue() === $value : $this->config->getValue() == $value;

        if($comparison){
            return;
        }

        throw new \LogicException(
            sprintf(
                'Given value "%s" is not%sequal to %s',
                is_scalar($value) ? var_export($value, true) : gettype($value),
                $this->config->isStrict() ? ' strictly ' : ' ',
                $this->config->getValue()
            )
        );
    }

    public function assertFalse($value): void
    {
        $comparison = $this->config->isStrict() ? $this->config->getValue() === $value : $this->config->getValue() == $value;

        if(!$comparison){
            return;
        }

        throw new \LogicException(
            sprintf(
                'Given value "%s" must NOT be%sequal to "%s"',
                is_scalar($value) ? var_export($value, true) : gettype($value),
                $this->config->isStrict() ? ' strictly ' : ' ',
                $this->config->getValue()
            )
        );
    }

    /**
     * @param ValidatorConfigInterface $config
     * @return StringEqualsValidator
     * @throws InvalidConfigException
     */
    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface
    {
        if(false === $config instanceof StringEqualsValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new InvalidConfigException($msg);
        }

        /**
         * @var StringEqualsValidatorConfig $config
         */
        return new self($config->getValue(), $config->isNegated());
    }

    /**
     * @return StringEqualsValidatorConfig
     */
    public function getConfig(): StringEqualsValidatorConfig
    {
        return $this->config;
    }
}