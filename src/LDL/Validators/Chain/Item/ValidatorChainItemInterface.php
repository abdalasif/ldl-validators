<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Item;

use LDL\Validators\ValidatorInterface;

interface ValidatorChainItemInterface
{
    /**
     * @return bool
     */
    public function isDumpable() : bool;

    /**
     * @return ValidatorInterface
     */
    public function getValidator() : ValidatorInterface;
}