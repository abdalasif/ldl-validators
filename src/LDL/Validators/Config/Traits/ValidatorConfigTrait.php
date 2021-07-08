<?php declare(strict_types=1);

namespace LDL\Validators\Config\Traits;


trait ValidatorConfigTrait
{
    /**
     * @var bool
     */
    private $_tDumpable;

    public function isDumpable() : bool
    {
        return $this->_tDumpable;
    }

    public function jsonSerialize() : array
    {
        return $this->toArray();
    }
}