<?php declare(strict_types=1);

namespace LDL\Validators\Traits;

trait ValidatorDescriptionTrait
{
    /**
     * @var string
     */
    private $_tDescription;

    //<editor-fold desc="ValidatorInterface methods">
    /**
     * @return string
     */
    public function getDescription() : string
    {
        return $this->_tDescription;
    }
    //</editor-fold>
}