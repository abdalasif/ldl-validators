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

    /**
     * @var ?string
     */
    private $_tDescription;

    public function isDumpable() : bool
    {
        return $this->_tDumpable;
    }

    public function isNegated() : bool
    {
        return $this->_tNegated;
    }

    public function hasDescription() : bool
    {
        return null !== $this->_tDescription;
    }

    /**
     * @return string|null
     */
    public function getDescription() : ?string
    {
        return $this->_tDescription;
    }

    public function jsonSerialize() : array
    {
        return $this->toArray();
    }

}