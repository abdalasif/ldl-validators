<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Exception\TypeMismatchException;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\Traits\ValidatorDescriptionTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class ScalarValidator implements ValidatorInterface, NegatedValidatorInterface, ValidatorHasConfigInterface
{
    use ValidatorValidateTrait;
    use NegatedValidatorTrait;
    use ValidatorDescriptionTrait;

    private const DESCRIPTION = 'Validate scalar';

    /**
     * @var bool
     */
    private $acceptToStringObjects;

    public function __construct(bool $acceptToStringObjects=true, bool $negated=false, string $description=null)
    {
        $this->acceptToStringObjects = $acceptToStringObjects;
        $this->_tNegated = $negated;
        $this->_tDescription = $description ?? self::DESCRIPTION;
    }

    /**
     * @return bool
     */
    public function isAcceptToStringObjects(): bool
    {
        return $this->acceptToStringObjects;
    }

    public function assertTrue($value): void
    {
        if(is_scalar($value)){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must be of type scalar, "%s" was given',
            __CLASS__,
            gettype($value)
        );

        throw new TypeMismatchException($msg);
    }

    public function assertFalse($value): void
    {
        if(!is_scalar($value)){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must NOT be of type scalar, "%s" was given',
            __CLASS__,
            gettype($value)
        );

        throw new TypeMismatchException($msg);
    }

    public function jsonSerialize(): array
    {
        return $this->getConfig();
    }

    /**
     * @param array $data
     * @return ValidatorInterface
     */
    public static function fromConfig(array $data = []): ValidatorInterface
    {
        return new self(
            array_key_exists('acceptToStringObjects', $data) ? (bool) $data['acceptToStringObjects'] : true,
            array_key_exists('negated', $data) ? (bool)$data['negated'] : false,
            $data['description'] ?? null
        );
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'acceptToStringObjects' => $this->acceptToStringObjects,
            'negated' => $this->_tNegated,
            'description' => $this->getDescription()
        ];
    }
}