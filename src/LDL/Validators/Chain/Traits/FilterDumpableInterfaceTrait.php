<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Traits;

use LDL\Framework\Helper\ClassRequirementHelperTrait;
use LDL\Validators\Chain\Item\Collection\ValidatorChainItemCollectionInterface;
use LDL\Validators\Chain\Item\ValidatorChainItemInterface;

trait FilterDumpableInterfaceTrait
{
    use ClassRequirementHelperTrait;

    /**
     * @return ValidatorChainItemCollectionInterface
     * @throws \Exception
     */
    public function filterDumpableItems(): ValidatorChainItemCollectionInterface
    {
        $this->requireImplements([ValidatorChainItemCollectionInterface::class]);

        $self = $this->getEmptyInstance();

        /**
         * @var ValidatorChainItemInterface $chainItem
         */
        foreach($this as $key => $chainItem){
            if(!$chainItem->isDumpable()){
                continue;
            }

            $self->append($chainItem, $key);
        }

        return $self;
    }
}