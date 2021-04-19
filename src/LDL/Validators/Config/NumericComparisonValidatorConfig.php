<?php declare(strict_types=1);

namespace LDL\Validators\Config;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Framework\Base\Exception\ArrayFactoryException;
use LDL\Validators\Config\Traits\ValidatorConfigTrait;

class NumericComparisonValidatorConfig implements ValidatorConfigInterface
{
    use ValidatorConfigTrait;

    /**
     * @TODO These constants should be part of an ArithmeticConstants file in ldl-framework-base
     */
    public const OPERATOR_EQ='==';
    public const OPERATOR_SEQ='===';
    public const OPERATOR_GT='>';
    public const OPERATOR_GTE='>=';
    public const OPERATOR_LT='<';
    public const OPERATOR_LTE='<=';

    /**
     * @var number
     */
    private $value;

    /**
     * @var string
     */
    private $operator;

    public function __construct($value, string $operator, bool $negated=false, bool $dumpable=true)
    {

        if(null !== $value && false === filter_var($value, \FILTER_VALIDATE_INT | \FILTER_VALIDATE_FLOAT)){
            $msg = sprintf(
                'Given value must be a number: "%s" was given',
                gettype($value)
            );

            throw new \InvalidArgumentException($msg);
        }

        $validOperators = [
            self::OPERATOR_EQ,
            self::OPERATOR_SEQ,
            self::OPERATOR_LT,
            self::OPERATOR_LTE,
            self::OPERATOR_GT,
            self::OPERATOR_GTE
        ];

        if(!in_array($operator, $validOperators,true)){
            throw new \InvalidArgumentException(
                sprintf('Invalid operator "%s", valid operators are: %s', implode(', ', $validOperators))
            );
        }

        $this->value = $value;
        $this->operator  = $operator;
        $this->_tNegated = $negated;
        $this->_tDumpable = $dumpable;
    }

    /**
     * @return number
     */
    public function getValue()
    {
        return $this->value;
    }

    public function getOperator() : string
    {
        return $this->operator;
    }

    /**
     * @param array $data
     * @return ValidatorConfigInterface
     * @throws ArrayFactoryException
     */
    public static function fromArray(array $data = []): ArrayFactoryInterface
    {
        if(false === array_key_exists('value', $data)){
            $msg = sprintf("Missing property 'value' in %s", __CLASS__);
            throw new ArrayFactoryException($msg);
        }

        try{
            return new self(
                $data['value'],
                array_key_exists('negated', $data) ? (bool)$data['negated'] : false,
                array_key_exists('dumpable', $data) ? (bool)$data['dumpable'] : true
            );
        }catch(\Exception $e){
            throw new ArrayFactoryException($e->getMessage());
        }

    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'negated' => $this->_tNegated,
            'dumpable' => $this->_tDumpable
        ];
    }
}