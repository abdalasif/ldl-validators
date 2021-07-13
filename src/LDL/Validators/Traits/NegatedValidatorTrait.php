<?php declare(strict_types=1);

namespace LDL\Validators\Traits;

trait NegatedValidatorTrait
{
    /**
     * @var bool
     */
    private $_tNegated;

    //<editor-fold desc="NegatedValidatorInterface methods">
    public function isNegated() : bool
    {
        return $this->_tNegated;
    }
    //</editor-fold>
}