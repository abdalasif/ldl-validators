<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\Exception\InvalidConfigException;
use LDL\Validators\Config\NumericComparisonValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Exception\NumericComparisonValidatorException;

class NumericComparisonValidator implements ValidatorInterface
{
    /**
     * @var NumericComparisonValidatorConfig
     */
    private $config;

    public function __construct($value, string $operator, bool $negated=false, bool $dumpable=true)
    {
        $this->config = new NumericComparisonValidatorConfig($value, $operator, $negated, $dumpable);
    }

    /**
     * @param mixed $value
     * @throws NumericComparisonValidatorException
     */
    public function validate($value): void
    {
        if($value <= $this->config->getValue()){
            return;
        }

        $msg = "Value can not be greater than: {$this->config->getValue()}";
        throw new NumericComparisonValidatorException($msg);
    }

    public function assertTrue($value): void
    {
        $compare = $this->compare($value);

        if($compare){
            return;
        }

        $msg = sprintf(
            'Value must be "%s" than "%s"; "%s" was given.',
            $this->config->getOperator(),
            $this->config->getValue(),
            $value
        );

        throw new NumericComparisonValidatorException($msg);
    }

    public function assertFalse($value): void
    {
        $compare = $this->compare($value);

        if(!$compare){
            return;
        }

        $msg = sprintf(
            'Value can NOT be "%s" than "%s"; "%s" was given.',
            $this->config->getOperator(),
            $this->config->getValue(),
            $value
        );

        throw new NumericComparisonValidatorException($msg);
    }

    private function compare($value) : bool
    {
        switch($this->config->getOperator()){
            case NumericComparisonValidatorConfig::OPERATOR_SEQ:
                return $value === $this->config->getValue();

            case NumericComparisonValidatorConfig::OPERATOR_EQ:
                return $value == $this->config->getValue();

            case NumericComparisonValidatorConfig::OPERATOR_GT:
                return $value > $this->config->getValue();

            case NumericComparisonValidatorConfig::OPERATOR_GTE:
                return $value >= $this->config->getValue();

            case NumericComparisonValidatorConfig::OPERATOR_LT:
                return $value < $this->config->getValue();

            case NumericComparisonValidatorConfig::OPERATOR_LTE:
                return $value <= $this->config->getValue();

            default:
                throw new \RuntimeException('Given operator is invalid (WTF?)');
        }
    }

    /**
     * @param ValidatorConfigInterface $config
     * @return MaxNumericValidator
     * @throws InvalidConfigException
     */
    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface
    {
        if(false === $config instanceof NumericComparisonValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new InvalidConfigException($msg);
        }

        /**
         * @var NumericComparisonValidatorConfig $config
         */
        return new self($config->getValue(), $config->getOperator(), $config->isNegated(), $config->isDumpable());
    }

    /**
     * @return NumericComparisonValidatorConfig
     */
    public function getConfig(): NumericComparisonValidatorConfig
    {
        return $this->config;
    }
}
