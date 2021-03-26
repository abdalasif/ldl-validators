<?php declare(strict_types=1);

namespace LDL\Validators\Config;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;

class ScalarValidatorConfig implements ValidatorConfigInterface
{
    use ValidatorConfigInterfaceTrait;

    /**
     * @var bool
     */
    private $acceptToStringObjects;

    public function __construct(
        bool $strict = false,
        bool $acceptToStringObjects=true
    )
    {
        $this->_isStrict = $strict;
        $this->acceptToStringObjects = $acceptToStringObjects;
    }

    /**
     * @return bool
     */
    public function isAcceptToStringObjects(): bool
    {
        return $this->acceptToStringObjects;
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
        $value = array_key_exists('acceptToStringObjects', $data) ? (bool) $data['acceptToStringObjects'] : true;
        $strict = array_key_exists('strict', $data) ? (bool) $data['strict'] : false;

        return new self($strict, $value);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'strict' => $this->_isStrict,
            'acceptToStringObjects' => $this->acceptToStringObjects
        ];
    }
}