<?php declare(strict_types=1);

namespace LDL\Validators\Config\Traits;


trait NegatedValidatorConfigTrait
{
    /**
     * @var bool
     */
    private $_tNegated;

    public function isNegated() : bool
    {
        return $this->_tNegated;
    }
}