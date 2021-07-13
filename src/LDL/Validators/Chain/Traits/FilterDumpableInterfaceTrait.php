<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Traits;

use LDL\Validators\Chain\Item\ValidatorChainItemInterface;
use LDL\Validators\Chain\ValidatorChainInterface;

trait FilterDumpableInterfaceTrait
{
    /**
     * @return ValidatorChainInterface
     * @throws \Exception
     */
    public function filterDumpableItems(): ValidatorChainInterface
    {
        $self = $this->getEmptyInstance();

        /**
         * @var ValidatorChainItemInterface $validator
         */
        foreach($this as $key => $validator){
            if(!$validator->isDumpable()){
                continue;
            }

            $self->append($validator, $key);
        }

        return $self;
    }
}