<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\Exception\InvalidConfigException;
use LDL\Validators\Config\RegexValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Traits\ValidatorValidateTrait;

class RegexValidator implements ValidatorInterface, NegatedValidatorInterface
{
    use ValidatorValidateTrait {validate as _validate;}

    /**
     * @var RegexValidatorConfig
     */
    private $config;

    public function __construct(string $regex, bool $negated=false, bool $dumpable=true, string $description=null)
    {
        $this->config = new RegexValidatorConfig($regex, $negated, $dumpable, $description);
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
        if(preg_match($this->config->getRegex(), (string) $value)) {
            return;
        }

        $msg = "Given value: \"$value\" does not matches regex: \"{$this->config->getRegex()}\"";
        throw new Exception\RegexValidatorException($msg);
    }

    public function assertFalse($value): void
    {
        if(!preg_match($this->config->getRegex(), (string) $value)) {
            return;
        }

        $msg = "Given value: \"$value\" matches regex: \"{$this->config->getRegex()}\"";
        throw new Exception\RegexValidatorException($msg);
    }

    /**
     * @param ValidatorConfigInterface $config
     * @return RegexValidator
     * @throws InvalidConfigException
     */
    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface
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
        return new self($config->getRegex(), $config->isNegated(), $config->isDumpable());
    }

    /**
     * @return RegexValidatorConfig
     */
    public function getConfig(): ValidatorConfigInterface
    {
        return $this->config;
    }
}