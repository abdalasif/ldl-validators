<?php declare(strict_types=1);

namespace LDL\Validators\Config;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;

class NumberValidatorConfig implements ValidatorConfigInterface
{
    use ValidatorConfigInterfaceTrait;

    public function __construct(bool $strict=true)
    {
        $this->_isStrict = $strict;
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
     */
    public static function fromArray(array $data = []): ArrayFactoryInterface
    {
        return new self(array_key_exists('strict', $data) ? (bool)$data['strict'] : true);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'strict' => $this->_isStrict
        ];
    }
}