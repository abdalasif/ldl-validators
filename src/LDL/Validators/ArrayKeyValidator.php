<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Framework\Base\Contracts\Type\ToArrayInterface;
use LDL\Framework\Base\Contracts\Type\ToArrayKeyInterface;
use LDL\Validators\Exception\TypeMismatchException;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\Traits\ValidatorDescriptionTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class ArrayKeyValidator implements ValidatorInterface, NegatedValidatorInterface
{
    use ValidatorValidateTrait;
    use NegatedValidatorTrait;
    use ValidatorDescriptionTrait;

    private const DESCRIPTION = 'Validate array key';

    public function __construct(
        bool $negated = false,
        string $description=null
    )
    {
        $this->_tNegated = $negated;
        $this->_tDescription = $description ?? self::DESCRIPTION;
    }

    public function assertTrue($value): void
    {
        if((is_string($value) || is_int($value)) || $value instanceof ToArrayKeyInterface){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must be of type int|string or an object which implements "%s", "%s" was given',
            __CLASS__,
            ToArrayKeyInterface::class,
            gettype($value)
        );

        throw new TypeMismatchException($msg);
    }

    public function assertFalse($value): void
    {
        if(!is_string($value) && !is_int($value) && !$value instanceof ToArrayKeyInterface){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must NOT be of type int or string or an object which implements "%s", "%s" was given',
            __CLASS__,
            ToArrayInterface::class,
            gettype($value)
        );

        throw new TypeMismatchException($msg);
    }
}