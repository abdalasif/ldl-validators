<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\ValidatorConfigInterface;

interface ValidatorHasConfigInterface
{
    /**
     * @param ValidatorConfigInterface $config
     * @return ValidatorConfigInterface
     */
    public static function fromConfig(ValidatorConfigInterface $config);

    /**
     * @return ValidatorConfigInterface
     */
    public function getConfig();
}