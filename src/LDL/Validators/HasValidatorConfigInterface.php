<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\ValidatorConfigInterface;

interface HasValidatorConfigInterface
{
    /**
     * @param ValidatorConfigInterface $config
     * @return ValidatorInterface
     */
    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface;

    /**
     * @return ValidatorConfigInterface
     */
    public function getConfig();
}