<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Exception\NumericComparisonValidatorException;
use LDL\Framework\Helper\ComparisonOperatorHelper;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class NumericComparisonValidator implements ValidatorInterface, NegatedValidatorInterface, ValidatorHasConfigInterface
{
    use ValidatorValidateTrait;
    use NegatedValidatorTrait;

    /**
     * @var number
     */
    private $value;

    /**
     * @var string
     */
    private $operator;

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
        if(null !== $value && false === filter_var($value, \FILTER_VALIDATE_INT | \FILTER_VALIDATE_FLOAT)){
            $msg = sprintf(
                'Given value must be a number: "%s" was given',
                gettype($value)
            );

            throw new \InvalidArgumentException($msg);
        }

        ComparisonOperatorHelper::validate($operator);

        $this->value = $value;
        $this->operator  = $operator;
        $this->_tNegated = $negated;
        $this->description = $description;
    }

    /**
     * @return number
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getOperator() : string
    {
        return $this->operator;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        if(!$this->description){
            return sprintf(
                'Number is %s than %s',
                $this->operator,
                $this->value
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
            $this->operator,
            $this->value,
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
            $this->operator,
            $this->value,
            $value
        );

        throw new NumericComparisonValidatorException($msg);
    }

    private function compare($value) : bool
    {
        switch($this->operator){
            case ComparisonOperatorHelper::OPERATOR_SEQ:
                return $value === $this->value;

            case ComparisonOperatorHelper::OPERATOR_EQ:
                return $value == $this->value;

            case ComparisonOperatorHelper::OPERATOR_GT:
                return $value > $this->value;

            case ComparisonOperatorHelper::OPERATOR_GTE:
                return $value >= $this->value;

            case ComparisonOperatorHelper::OPERATOR_LT:
                return $value < $this->value;

            case ComparisonOperatorHelper::OPERATOR_LTE:
                return $value <= $this->value;

            default:
                throw new \RuntimeException('Given operator is invalid (WTF?)');
        }
    }

    public function jsonSerialize(): array
    {
        return $this->getConfig();
    }

    /**
     * @param array $data
     * @return ValidatorInterface
     * @throws Exception\TypeMismatchException
     */
    public static function fromConfig(array $data = []): ValidatorInterface
    {
        if(!array_key_exists('value', $data)){
            $msg = sprintf("Missing property 'value' in %s", __CLASS__);
            throw new Exception\TypeMismatchException($msg);
        }

        if(!array_key_exists('operator', $data)){
            $msg = sprintf("Missing property 'operator' in %s", __CLASS__);
            throw new Exception\TypeMismatchException($msg);
        }

        if(!is_string($data['operator'])){
            throw new \InvalidArgumentException(
                sprintf('operator must be of type string, "%s" was given',gettype($data['operator']))
            );
        }

        try{
            return new self(
                $data['value'],
                $data['operator'],
                array_key_exists('negated', $data) ? (bool)$data['negated'] : false,
                $data['description'] ?? null
            );
        }catch(\Exception $e){
            throw new Exception\TypeMismatchException($e->getMessage());
        }
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'value' => $this->value,
            'operator' => $this->operator,
            'negated' => $this->_tNegated,
            'description' => $this->getDescription()
        ];
    }
}
