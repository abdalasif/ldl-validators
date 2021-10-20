<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Framework\Base\Contracts\Type\ToDoubleInterface;
use LDL\Validators\Exception\TypeMismatchException;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\Traits\ValidatorDescriptionTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class DoubleValidator implements ValidatorInterface, NegatedValidatorInterface
{
    use ValidatorValidateTrait;
    use NegatedValidatorTrait;
    use ValidatorDescriptionTrait;

    private const DESCRIPTION = 'Validate double';

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
        $instanceOfToDouble = $value instanceof ToDoubleInterface;
        $valid = is_float($value) || $instanceOfToDouble;

        if($valid && !$this->unsigned){
            return;
        }

        $value = $instanceOfToDouble ? $value->toDouble() : $value;

        if($valid && $this->unsigned && $value < 0){
            throw new TypeMismatchException("Only unsigned numbers are allowed \"$value\" was given");
        }

        if($valid){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must be of type double or an instance of "%s", "%s" given',
            __CLASS__,
            ToDoubleInterface::class,
            gettype($value)
        );

        throw new TypeMismatchException($msg);
    }

    public function assertFalse($value): void
    {
        $instanceOfToDouble = $value instanceof ToDoubleInterface;

        if(!is_float($value) && !$instanceOfToDouble){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must NOT be of type double or an instance of "%s"',
            __CLASS__,
            ToDoubleInterface::class
        );

        throw new TypeMismatchException($msg);
    }
}