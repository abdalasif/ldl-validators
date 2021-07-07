<?php declare(strict_types=1);

namespace LDL\Validators\Traits;

use LDL\Validators\Config\ValidatorConfigInterface;

trait ValidatorHasConfigInterfaceTrait
{
    /**
     * @var ValidatorConfigInterface
     */
    private $_tConfig;

    //<editor-fold desc="ValidatorHasConfigInterface methods">
    /**
     * @return ValidatorConfigInterface
     */
    public function getConfig() : ValidatorConfigInterface
    {
        return $this->_tConfig;
    }
    //</editor-fold>
}