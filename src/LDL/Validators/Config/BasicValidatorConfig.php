<?php declare(strict_types=1);

namespace LDL\Validators\Config;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Validators\Config\Traits\NegatedValidatorConfigTrait;
use LDL\Validators\Config\Traits\ValidatorConfigTrait;

class BasicValidatorConfig implements ValidatorConfigInterface, NegatedValidatorConfigInterface
{
    use ValidatorConfigTrait;
    use NegatedValidatorConfigTrait;

    public function __construct(
        bool $negated=false,
        bool $dumpable=true
    )
    {
        $this->_tNegated = $negated;
        $this->_tDumpable = $dumpable;
    }

    /**
     * @param array $data
     * @return ArrayFactoryInterface
     */
    public static function fromArray(array $data = []): ArrayFactoryInterface
    {
        return new self(
            array_key_exists('negated', $data) ? (bool)$data['negated'] : false,
            array_key_exists('dumpable', $data) ? (bool)$data['dumpable'] : true,
            array_key_exists('description', $data) ? (bool)$data['description'] : null,
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'negated' => $this->_tNegated,
            'dumpable' => $this->_tDumpable
        ];
    }
}