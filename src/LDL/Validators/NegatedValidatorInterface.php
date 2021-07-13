<?php declare(strict_types=1);

namespace LDL\Validators;

interface NegatedValidatorInterface
{
    /**
     * Assert negative (false) condition, for example in an integer validator, assert that the value is NOT of
     * integer type.
     *
     * @param $value
     * @throws \Exception
     */
    public function assertFalse($value) : void;

    /**
     * @return bool
     */
    public function isNegated() : bool;
}