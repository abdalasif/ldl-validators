<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Framework\Base\Contracts\Type\ToStringInterface;
use LDL\Validators\Exception\TypeMismatchException;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\Traits\ValidatorDescriptionTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class StringValidator implements ValidatorInterface, NegatedValidatorInterface
{
    use ValidatorValidateTrait;
    use NegatedValidatorTrait;
    use ValidatorDescriptionTrait;

    private const DESCRIPTION = 'Validate string';

    public function __construct(bool $negated=false, string $description=null)
    {
        $this->_tNegated = $negated;
        $this->_tDescription = $description ?? self::DESCRIPTION;
    }

    public function assertTrue($value): void
    {
        if($value instanceof ToStringInterface) {
            $value = $value->toString();
        }

        if(is_string($value)){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must be of type string, "%s" was given',
            __CLASS__,
            gettype($value)
        );

        throw new TypeMismatchException($msg);
    }

    public function assertFalse($value): void
    {
        if(!is_string($value) && !$value instanceof ToStringInterface){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must NOT be of type string or an instanceof "%s", "%s" was given',
            __CLASS__,
            ToStringInterface::class,
            gettype($value)
        );

        throw new TypeMismatchException($msg);
    }
}