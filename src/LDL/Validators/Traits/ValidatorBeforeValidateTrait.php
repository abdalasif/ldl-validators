<?php declare(strict_types=1);

namespace LDL\Validators\Traits;

use LDL\Framework\Base\Collection\CallableCollection;
use LDL\Framework\Base\Collection\CallableCollectionInterface;

trait ValidatorBeforeValidateTrait
{
    /**
     * @var bool
     */
    private $_tBeforeValidate;

    //<editor-fold desc="BeforeValidateInterface methods">
    public function onBeforeValidate() : CallableCollectionInterface
    {
        if(null === $this->_tBeforeValidate){
            $this->_tBeforeValidate = new CallableCollection();
        }

        return $this->_tBeforeValidate;
    }
    //</editor-fold>
}