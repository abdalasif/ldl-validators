<?php declare(strict_types=1);

namespace LDL\Validators\Config;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Framework\Base\Exception\ArrayFactoryException;
use LDL\Validators\Config\Traits\ValidatorConfigTrait;

class StringEqualsValidatorConfig implements ValidatorConfigInterface
{
    use ValidatorConfigTrait;

    /**
     * @var bool
     */
    private $strict;

    /**
     * @var string
     */
    private $value;

    public function __construct(
        string $value,
        bool $strict=true,
        bool $negated=false,
        bool $dumpable=true
    )
    {
        $this->_tNegated = $negated;
        $this->_tDumpable = $dumpable;

        $this->strict = $strict;
        $this->value = $value;
    }

    public function isStrict() : bool
    {
        return $this->strict;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
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
                (string) $data['value'],
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