<?php declare(strict_types=1);

namespace LDL\Validators\Chain;

use LDL\Validators\NegatedValidatorInterface;
use LDL\Validators\ValidatorHasConfigInterface;

interface BooleanValidatorChainInterface extends ValidatorChainInterface, ValidatorHasConfigInterface, NegatedValidatorInterface, ValidatorChainHasOperatorInterface
{

}