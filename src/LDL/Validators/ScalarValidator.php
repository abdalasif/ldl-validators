<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Exception\TypeMismatchException;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\Traits\ValidatorDescriptionTrait;
use LDL\Validators\Traits\ValidatorHasConfigInterfaceTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class ScalarValidator implements ValidatorInterface, NegatedValidatorInterface
{
    use ValidatorValidateTrait;
    use NegatedValidatorTrait;
    use ValidatorHasConfigInterfaceTrait;
    use ValidatorDescriptionTrait;

    private const DESCRIPTION = 'Validate scalar';

    public function __construct(bool $negated=false, string $description=null)
    {
        $this->_tNegated = $negated;
        $this->_tDescription = $description ?? self::DESCRIPTION;
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
}