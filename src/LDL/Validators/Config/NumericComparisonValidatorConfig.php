<?php declare(strict_types=1);

namespace LDL\Validators\Config;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Framework\Base\Exception\ArrayFactoryException;
use LDL\Framework\Helper\ComparisonOperatorHelper;
use LDL\Validators\Config\Traits\ValidatorConfigTrait;

class NumericComparisonValidatorConfig implements ValidatorConfigInterface
{
    use ValidatorConfigTrait;

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

        ComparisonOperatorHelper::validate($operator);

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
        if(!array_key_exists('value', $data)){
            $msg = sprintf("Missing property 'value' in %s", __CLASS__);
            throw new ArrayFactoryException($msg);
        }

        if(!array_key_exists('operator', $data)){
            $msg = sprintf("Missing property 'operator' in %s", __CLASS__);
            throw new ArrayFactoryException($msg);
        }

        if(!is_string($data['operator'])){
            throw new \InvalidArgumentException(
                sprintf('operator must be of type string, "%s" was given',gettype($data['operator']))
            );
        }

        try{
            return new self(
                $data['value'],
                array_key_exists('operator', $data) ? $data['operator'] : false,
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
            'operator' => $this->operator,
            'negated' => $this->_tNegated,
            'dumpable' => $this->_tDumpable
        ];
    }
}