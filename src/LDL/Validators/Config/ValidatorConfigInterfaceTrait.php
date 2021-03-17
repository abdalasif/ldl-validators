<?php declare(strict_types=1);

namespace LDL\Validators\Config;

trait ValidatorConfigInterfaceTrait
{
    /**
     * @var bool
     */
    private $_isStrict;

    public function isStrict() : bool
    {
        return $this->_isStrict;
    }
}