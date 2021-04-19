<?php declare(strict_types=1);

namespace LDL\Validators\Config;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Validators\Config\Traits\ValidatorConfigTrait;

class ScalarValidatorConfig implements ValidatorConfigInterface
{
    use ValidatorConfigTrait;

    /**
     * @var bool
     */
    private $acceptToStringObjects;

    public function __construct(
        bool $acceptToStringObjects=true,
        bool $negated=false,
        bool $dumpable=true
    )
    {
        $this->acceptToStringObjects = $acceptToStringObjects;
        $this->_tNegated = $negated;
        $this->_tDumpable = $dumpable;
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
        return new self(
            array_key_exists('acceptToStringObjects', $data) ? (bool) $data['acceptToStringObjects'] : true,
            array_key_exists('negated', $data) ? (bool)$data['negated'] : false,
            array_key_exists('dumpable', $data) ? (bool)$data['dumpable'] : true
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'acceptToStringObjects' => $this->acceptToStringObjects,
            'negated' => $this->_tNegated,
            'dumpable' => $this->_tDumpable
        ];
    }
}