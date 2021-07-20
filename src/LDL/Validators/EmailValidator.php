<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Exception\TypeMismatchException;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\Traits\ValidatorDescriptionTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class EmailValidator implements ValidatorInterface, NegatedValidatorInterface
{
    use ValidatorValidateTrait;
    use NegatedValidatorTrait;
    use ValidatorDescriptionTrait;

    private const DESCRIPTION = 'Validate email';

    public function __construct(bool $negated=false, string $description=null)
    {
        $this->_tNegated = $negated;
        $this->_tDescription = $description ?? self::DESCRIPTION;
    }

    public function assertTrue($value): void
    {
        if(filter_var($value, \FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must be a valid email',
            __CLASS__
        );

        throw new TypeMismatchException($msg);
    }

    public function assertFalse($value): void
    {
        if(!filter_var($value, \FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", can not be an email address',
            __CLASS__
        );

        throw new TypeMismatchException($msg);
    }
}