<?php declare(strict_types=1);

namespace LDL\Validators\Traits;

use LDL\Framework\Helper\ClassRequirementHelperTrait;
use LDL\Validators\Config\NegatedValidatorConfigInterface;
use LDL\Validators\ValidatorInterface;

trait ValidatorValidateTrait
{
    use ClassRequirementHelperTrait;

    public function validate($value, ...$params): void
    {
        $this->requireImplements([ValidatorInterface::class]);

        $config = $this->getConfig();

        if($config instanceof NegatedValidatorConfigInterface){
            $config->isNegated() ? $this->assertFalse($value, ...$params) : $this->assertTrue($value, ...$params);
            return;
        }

        $this->assertTrue($value, ...$params);
    }
}