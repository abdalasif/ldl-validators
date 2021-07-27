<?php declare(strict_types=1);

namespace LDL\Validators\Chain;

interface ValidatorChainHasOperatorInterface
{
    public function getOperator(): string;
}