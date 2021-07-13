<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\ValidatorConfigInterface;

interface ValidatorHasConfigInterface
{
    /**
     * @param ValidatorConfigInterface $config
     * @param bool $negated
     * @param string|null $description
     * @return ValidatorConfigInterface
     */
    public static function fromConfig(
        ValidatorConfigInterface $config,
        bool $negated = false,
        string $description=null
    );

    /**
     * @return ValidatorConfigInterface
     */
    public function getConfig();
}