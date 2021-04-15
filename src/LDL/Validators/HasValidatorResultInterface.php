<?php declare(strict_types=1);

namespace LDL\Validators;

interface HasValidatorResultInterface
{
    /**
     * @return mixed
     */
    public function getResult();
}