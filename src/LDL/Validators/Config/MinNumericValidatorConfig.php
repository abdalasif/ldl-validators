<?php declare(strict_types=1);

namespace LDL\Validators\Config;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Framework\Base\Exception\ArrayFactoryException;

class MinNumericValidatorConfig implements ValidatorConfigInterface
{
    use ValidatorConfigInterfaceTrait;

    /**
     * @var number
     */
    private $value;

    /**
     * MinNumericValueValidator constructor.
     * @param $value
     * @param bool $isStrict
     */
    public function __construct($value, bool $isStrict=true)
    {
        $this->_isStrict = $isStrict;

        if(null !== $value && false === filter_var($value, \FILTER_VALIDATE_INT | \FILTER_VALIDATE_FLOAT)){
            throw new \InvalidArgumentException("Given minimum value must be a number, \"$value\" was given");
        }

        $this->value = $value;
    }

    /**
     * @return number
     */
    public function getValue()
    {
        return $this->value;
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
     * @return ArrayFactoryInterface
     * @throws ArrayFactoryException
     */
    public static function fromArray(array $data = []): ArrayFactoryInterface
    {
        if(false === array_key_exists('value', $data)){
            $msg = sprintf("Missing property 'value' in %s", __CLASS__);
            throw new ArrayFactoryException($msg);
        }

        try{
            return new self($data['value'], array_key_exists('strict', $data) ? (bool)$data['strict'] : true);
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
            'strict' => $this->_isStrict
        ];
    }
}