<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\Exception\InvalidConfigException;
use LDL\Validators\Config\StringEqualsValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Traits\ValidatorDescriptionTrait;
use LDL\Validators\Traits\ValidatorHasConfigInterfaceTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class StringEqualsValidator implements ValidatorInterface, NegatedValidatorInterface, ValidatorHasConfigInterface
{
    use ValidatorValidateTrait;
    use ValidatorHasConfigInterfaceTrait;
    use ValidatorDescriptionTrait;

    private const DESCRIPTION = 'Validate equals strings';

    public function __construct(
        string $name,
        bool $strict=true,
        bool $negated=false,
        bool $dumpable=true,
        string $description=null
    )
    {
        $this->_tConfig = new StringEqualsValidatorConfig($name, $strict, $negated, $dumpable);
        $this->_tDescription = $description ?? self::DESCRIPTION;
    }

    public function assertTrue($value): void
    {
        $comparison = $this->_tConfig->isStrict() ? $this->_tConfig->getValue() === $value : $this->_tConfig->getValue() == $value;

        if($comparison){
            return;
        }

        throw new \LogicException(
            sprintf(
                'Given value "%s" is not%sequal to %s',
                is_scalar($value) ? var_export($value, true) : gettype($value),
                $this->_tConfig->isStrict() ? ' strictly ' : ' ',
                $this->_tConfig->getValue()
            )
        );
    }

    public function assertFalse($value): void
    {
        $comparison = $this->_tConfig->isStrict() ? $this->_tConfig->getValue() === $value : $this->_tConfig->getValue() == $value;

        if(!$comparison){
            return;
        }

        throw new \LogicException(
            sprintf(
                'Given value "%s" must NOT be%sequal to "%s"',
                is_scalar($value) ? var_export($value, true) : gettype($value),
                $this->_tConfig->isStrict() ? ' strictly ' : ' ',
                $this->_tConfig->getValue()
            )
        );
    }

    /**
     * @param ValidatorConfigInterface $config
     * @param string|null $description
     * @return ValidatorInterface
     * @throws InvalidConfigException
     */
    public static function fromConfig(ValidatorConfigInterface $config, string $description=null): ValidatorInterface
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
        return new self(
            $config->getValue(),
            $config->isStrict(),
            $config->isNegated(),
            $config->isDumpable(),
            $description
        );
    }
}