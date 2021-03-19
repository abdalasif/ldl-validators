<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Exception\ValidatorException;

interface ValidatorInterface
{
    /**
     * @param mixed $value
     * @throws ValidatorException
     */
    public function validate($value) : void;
}