<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\Exception\InvalidConfigException;
use LDL\Validators\Config\NumericComparisonValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Exception\NumericComparisonValidatorException;
use LDL\Framework\Helper\ComparisonOperatorHelper;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\Traits\ValidatorHasConfigInterfaceTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class NumericComparisonValidator implements ValidatorInterface, NegatedValidatorInterface, ValidatorHasConfigInterface
{
    use ValidatorValidateTrait;
    use NegatedValidatorTrait;
    use ValidatorHasConfigInterfaceTrait;

    /**
     * @var string|null
     */
    private $description;

    public function __construct(
        $value,
        string $operator,
        bool $negated=false,
        string $description=null
    )
    {
        $this->_tConfig = new NumericComparisonValidatorConfig($value, $operator);
        $this->_tNegated = $negated;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        if(!$this->description){
            return sprintf(
                'Number is %s than %s',
                $this->_tConfig->getOperator(),
                $this->_tConfig->getValue()
            );
        }

        return $this->description;
    }

    public function assertTrue($value): void
    {
        $compare = $this->compare($value);

        if($compare){
            return;
        }

        $msg = sprintf(
            'Value must be "%s" than "%s"; "%s" was given.',
            $this->_tConfig->getOperator(),
            $this->_tConfig->getValue(),
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
            $this->_tConfig->getOperator(),
            $this->_tConfig->getValue(),
            $value
        );

        throw new NumericComparisonValidatorException($msg);
    }

    private function compare($value) : bool
    {
        switch($this->_tConfig->getOperator()){
            case ComparisonOperatorHelper::OPERATOR_SEQ:
                return $value === $this->_tConfig->getValue();

            case ComparisonOperatorHelper::OPERATOR_EQ:
                return $value == $this->_tConfig->getValue();

            case ComparisonOperatorHelper::OPERATOR_GT:
                return $value > $this->_tConfig->getValue();

            case ComparisonOperatorHelper::OPERATOR_GTE:
                return $value >= $this->_tConfig->getValue();

            case ComparisonOperatorHelper::OPERATOR_LT:
                return $value < $this->_tConfig->getValue();

            case ComparisonOperatorHelper::OPERATOR_LTE:
                return $value <= $this->_tConfig->getValue();

            default:
                throw new \RuntimeException('Given operator is invalid (WTF?)');
        }
    }

    /**
     * @param ValidatorConfigInterface $config
     * @param bool $negated
     * @param string|null $description
     * @return NumericComparisonValidator
     * @throws InvalidConfigException
     */
    public static function fromConfig(ValidatorConfigInterface $config, bool $negated = false, string $description=null): NumericComparisonValidator
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
        return new self(
            $config->getValue(),
            $config->getOperator(),
            $negated,
            $description
        );
    }
}
