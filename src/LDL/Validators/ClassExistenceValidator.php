<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Exception\TypeMismatchException;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\Traits\ValidatorDescriptionTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class ClassExistenceValidator implements ValidatorInterface, NegatedValidatorInterface
{
    use ValidatorValidateTrait;
    use NegatedValidatorTrait;
    use ValidatorDescriptionTrait;

    private const DESCRIPTION = 'Validate if class exist';

    public function __construct(bool $negated=false, string $description=null)
    {
        $this->_tNegated = $negated;
        $this->_tDescription = $description ?? self::DESCRIPTION;
    }

    public function assertTrue($value): void
    {
        if(class_exists($value)){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must be a string representing an existing class, "%s" was given. Perhaps an autoloader is missing? Perhaps the namespace of the class is written incorrectly?',
            __CLASS__,
            gettype($value)
        );

        throw new TypeMismatchException($msg);
    }

    /**
     * Might sound awkward, but who am I to state anything about use cases ¯\_(ツ)_/¯
     *
     * @TODO Perhaps format validation for the parameter would be necessary ?
     *
     * @param $value
     * @throws TypeMismatchException
     */
    public function assertFalse($value): void
    {
        if(!class_exists($value)){
            return;
        }

        $msg = sprintf(
            'Value expected for "%s", must be a string representing a NON existing class, "%s" was given.',
            __CLASS__,
            gettype($value)
        );

        throw new TypeMismatchException($msg);
    }
}