<?php declare(strict_types=1);

namespace LDL\Validators\Config;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Framework\Base\Exception\ArrayFactoryException;
use LDL\Validators\MaxNumericValidator;
use LDL\Validators\MinNumericValidator;

class NumericRangeValidatorConfig implements ValidatorConfigInterface
{
    use ValidatorConfigInterfaceTrait;

    /**
     * @var MinNumericValidator
     */
    private $minValidator;

    /**
     * @var MaxNumericValidator
     */
    private $maxValidator;

    /**
     * NumericRangeValidator constructor.
     * @param $min
     * @param $max
     * @param bool $strict
     *
     */
    public function __construct($min, $max, bool $strict = true)
    {
        $this->_isStrict = $strict;

        $this->minValidator = new MinNumericValidator($min, $strict);
        $this->maxValidator = new MaxNumericValidator($max, $strict);
    }

    /**
     * @return MinNumericValidator
     */
    public function getMin(): MinNumericValidator
    {
        return $this->minValidator;
    }

    /**
     * @return MaxNumericValidator
     */
    public function getMax(): MaxNumericValidator
    {
        return $this->maxValidator;
    }

    /**
     * @return array
     */
    public function jsonSerialize() : array
    {
        return $this->toArray();
    }

    /**
     * @param array $data
     * @return ValidatorConfigInterface
     * @throws ArrayFactoryException
     */
    public static function fromArray(array $data = []): ArrayFactoryInterface
    {
        if(false === array_key_exists('min', $data)){
            $msg = sprintf("Missing property 'min' in %s", __CLASS__);
            throw new ArrayFactoryException($msg);
        }

        if(false === array_key_exists('max', $data)){
            $msg = sprintf("Missing property 'max' in %s", __CLASS__);
            throw new ArrayFactoryException($msg);
        }

        return new self($data['min'], $data['max'], array_key_exists('strict', $data) ? (bool)$data['strict'] : true);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'min' => $this->minValidator->getConfig()->getValue(),
            'max' => $this->maxValidator->getConfig()->getValue()
        ];
    }
}