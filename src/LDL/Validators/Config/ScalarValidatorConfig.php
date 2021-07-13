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
        bool $acceptToStringObjects=true
    )
    {
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
     * @param array $data
     * @return ArrayFactoryInterface
     */
    public static function fromArray(array $data = []): ArrayFactoryInterface
    {
        return new self(
            array_key_exists('acceptToStringObjects', $data) ? (bool) $data['acceptToStringObjects'] : true
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'acceptToStringObjects' => $this->acceptToStringObjects
        ];
    }
}