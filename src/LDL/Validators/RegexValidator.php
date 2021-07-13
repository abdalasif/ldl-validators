<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\Exception\InvalidConfigException;
use LDL\Validators\Config\RegexValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\Traits\ValidatorHasConfigInterfaceTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class RegexValidator implements ValidatorInterface, NegatedValidatorInterface, ValidatorHasConfigInterface
{
    use ValidatorValidateTrait {validate as _validate;}
    use NegatedValidatorTrait;
    use ValidatorHasConfigInterfaceTrait;

    /**
     * @var string
     */
    private $description;

    public function __construct(string $regex, bool $negated=false, string $description=null)
    {
        $this->_tNegated = $negated;
        $this->_tConfig = new RegexValidatorConfig($regex);
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        if(!$this->description){
            return sprintf(
                'Validate regex with pattern: %s',
                $this->_tConfig->getRegex(),
            );
        }

        return $this->description;
    }

    public function validate($value): void
    {
        if(!is_scalar($value)){
            throw new \LogicException(sprintf('Validator %s only accepts scalar values', __CLASS__));
        }

        $this->_validate($value);
    }

    public function assertTrue($value): void
    {
        if(preg_match($this->_tConfig->getRegex(), (string) $value)) {
            return;
        }

        $msg = "Given value: \"$value\" does not matches regex: \"{$this->_tConfig->getRegex()}\"";
        throw new Exception\RegexValidatorException($msg);
    }

    public function assertFalse($value): void
    {
        if(!preg_match($this->_tConfig->getRegex(), (string) $value)) {
            return;
        }

        $msg = "Given value: \"$value\" matches regex: \"{$this->_tConfig->getRegex()}\"";
        throw new Exception\RegexValidatorException($msg);
    }

    /**
     * @param ValidatorConfigInterface $config
     * @param bool $negated
     * @param string|null $description
     * @return ValidatorInterface
     * @throws InvalidConfigException
     */
    public static function fromConfig(ValidatorConfigInterface $config, bool $negated = false, string $description=null): ValidatorInterface
    {
        if(false === $config instanceof RegexValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new InvalidConfigException($msg);
        }

        /**
         * @var RegexValidatorConfig $config
         */
        return new self($config->getRegex(), $negated, $description);
    }
}