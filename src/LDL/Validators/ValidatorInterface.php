<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\ValidatorConfigInterface;

interface ValidatorInterface
{
    /**
     * @param mixed $value
     * @throws \Exception
     */
    public function validate($value) : void;

    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface;

    public function getConfig() : ValidatorConfigInterface;

}