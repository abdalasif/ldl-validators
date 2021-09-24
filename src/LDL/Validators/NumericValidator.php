<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Exception\TypeMismatchException;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\Traits\ValidatorDescriptionTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class NumericValidator implements ValidatorInterface, NegatedValidatorInterface
{
    use ValidatorValidateTrait;
    use NegatedValidatorTrait;
    use ValidatorDescriptionTrait;

    private const DESCRIPTION = 'Validate numeric';

    /**
     * @var bool
     */
    private $unsigned;

    public function __construct(bool $negated=false, string $description=null, bool $unsigned=false)
    {
        $this->unsigned = $unsigned;
        $this->_tNegated = $negated;
        $this->_tDescription = $description ?? self::DESCRIPTION;
    }

    public function assertTrue($value): void
    {
        $valid = is_numeric($value);

        if($valid && !$this->unsigned){
            return;
        }

        if($valid && $this->unsigned && $value < 0){
            throw new TypeMismatchException("Only unsigned numbers are allowed \"$value\" was given");
        }

        if($valid){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must be of type numeric; "%s" was given',
            __CLASS__,
            is_scalar($value) ? var_export($value, true) : gettype($value)
        );

        throw new TypeMismatchException($msg);
    }

    public function assertFalse($value): void
    {
        if(!is_numeric($value)){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must not be numeric; "%s" was given',
            __CLASS__,
            is_scalar($value) ? var_export($value, true) : gettype($value)
        );

        throw new TypeMismatchException($msg);
    }
}