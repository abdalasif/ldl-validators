<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Framework\Base\Contracts\Type\ToIntegerInterface;
use LDL\Validators\Exception\TypeMismatchException;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\Traits\ValidatorDescriptionTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class IntegerValidator implements ValidatorInterface, NegatedValidatorInterface
{
    use ValidatorValidateTrait;
    use NegatedValidatorTrait;
    use ValidatorDescriptionTrait;

    private const DESCRIPTION = 'Validate integer';

    /**
     * @var bool
     */
    private $unsigned;

    public function __construct(bool $negated = false, string $description=null, bool $unsigned=false)
    {
        $this->unsigned = $unsigned;
        $this->_tNegated = $negated;
        $this->_tDescription = $description ?? self::DESCRIPTION;
    }

    public function assertTrue($value): void
    {
        if($value instanceof ToIntegerInterface){
            $value = $value->toInteger();
        }

        $valid = is_int($value);

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
            'Value expected for "%s", must be of type integer or an instance of "%s", "%s" was given',
            __CLASS__,
            ToIntegerInterface::class,
            gettype($value)
        );

        throw new TypeMismatchException($msg);
    }

    public function assertFalse($value): void
    {
        if(!is_int($value) && !$value instanceof ToIntegerInterface){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must NOT be of type integer or an instance of "%s", "%s" was given',
            __CLASS__,
            ToIntegerInterface::class,
            gettype($value)
        );

        throw new TypeMismatchException($msg);
    }
}