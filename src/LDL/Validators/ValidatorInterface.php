<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Exception\ValidatorException;

interface ValidatorInterface
{
    /**
     * @param mixed $value
     * @throws ValidatorException
     */
    public function validate($value) : void;

    /**
     * @param ValidatorConfigInterface $config
     * @return ValidatorInterface
     */
    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface;

    /**
     * @return ValidatorConfigInterface
     */
    public function getConfig(): ValidatorConfigInterface;

}