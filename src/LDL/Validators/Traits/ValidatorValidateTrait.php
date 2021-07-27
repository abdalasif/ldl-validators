<?php declare(strict_types=1);

namespace LDL\Validators\Traits;

use LDL\Framework\Helper\ClassRequirementHelperTrait;
use LDL\Validators\NegatedValidatorInterface;
use LDL\Validators\ValidatorInterface;

trait ValidatorValidateTrait
{
    use ClassRequirementHelperTrait;

    public function validate($value, ...$params): void
    {
        $this->requireImplements([ValidatorInterface::class]);

        if($this instanceof NegatedValidatorInterface && $this->isNegated()){
            $this->assertFalse($value, ...$params);
            return;
        }

        $this->assertTrue($value, ...$params);
    }
}