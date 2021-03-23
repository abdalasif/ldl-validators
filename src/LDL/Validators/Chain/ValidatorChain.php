<?php declare(strict_types=1);

namespace LDL\Validators\Chain;

use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Framework\Base\Collection\Traits\AppendableInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\AppendManyTrait;
use LDL\Framework\Base\Collection\Traits\BeforeAppendInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\BeforeRemoveInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\CollectionInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\LockAppendInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\RemovableInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\TruncateInterfaceTrait;
use LDL\Validators\Chain\Exception\CombinedException;
use LDL\Framework\Base\Traits\LockableObjectInterfaceTrait;
use LDL\Validators\HasValidatorConfigInterface;
use LDL\Validators\InterfaceComplianceValidator;
use LDL\Validators\ValidatorInterface;

class ValidatorChain implements ValidatorChainInterface
{
    use CollectionInterfaceTrait;
    use LockableObjectInterfaceTrait;
    use BeforeAppendInterfaceTrait;
    use AppendableInterfaceTrait {append as private _append;}
    use AppendManyTrait;
    use LockAppendInterfaceTrait;
    use RemovableInterfaceTrait;
    use BeforeRemoveInterfaceTrait;
    use TruncateInterfaceTrait {truncate as private _truncate;}

    /**
     * @var array
     */
    private $dumpable = [];

    public function __construct(iterable $validators=null)
    {
        $this->getBeforeAppend()->append(static function ($collection, $item, $key){
            (new InterfaceComplianceValidator(ValidatorInterface::class, true))->validate($item);
        });

        if(null !== $validators) {
            $this->appendMany($validators);
        }
    }

    public function validate($value) : void
    {
        if(0 === $this->count){
            return;
        }

        /**
         * @var \Exception[]
         */
        $combinedException = new CombinedException();
        $atLeastOneValid = false;

        /**
         * @var ValidatorInterface $validator
         */
        foreach($this as $validator){
            if(
                $validator instanceof HasValidatorConfigInterface &&
                true === $validator->getConfig()->isStrict()
            ){
                $validator->validate($value);
                $atLeastOneValid=true;
                continue;
            }

            try{
                $validator->validate($value);
                $atLeastOneValid = true;
            }catch(\Exception $e){
                $combinedException->append($e);
            }
        }

        if($atLeastOneValid){
            return;
        }

        throw new $combinedException;
    }

    public function append($item, $key = null, bool $dumpable = true): CollectionInterface
    {
        $return = $this->_append($item, $key);

        if(true === $dumpable) {
            $this->dumpable[] = $key;
        }

        return $return;
    }

    /**
     * @return ValidatorChainInterface
     * @throws \Exception
     */
    public function filterDumpableItems(): ValidatorChainInterface
    {
        $self = clone($this);
        $self->_truncate();
        $self->dumpable = [];

        foreach($this as $key => $item){
            if(in_array($key, $this->dumpable, true)){
                $self->append($item, $key);
            }
        }

        return $self;
    }

}