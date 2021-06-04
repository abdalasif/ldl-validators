<?php declare(strict_types=1);

namespace LDL\Validators\Config;

interface NegatedValidatorConfigInterface
{
    /**
     * @return bool
     */
    public function isNegated() : bool;
}