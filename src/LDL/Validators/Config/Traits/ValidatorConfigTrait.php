<?php declare(strict_types=1);

namespace LDL\Validators\Config\Traits;


trait ValidatorConfigTrait
{
    /**
     * @var bool
     */
    private $_tDumpable;

    /**
     * @var bool
     */
    private $_tNegated;

    public function isDumpable() : bool
    {
        return $this->_tDumpable;
    }

    public function isNegated() : bool
    {
        return $this->_tNegated;
    }

    public function jsonSerialize() : array
    {
        return $this->toArray();
    }

}