<?php declare(strict_types=1);

namespace LDL\Validators\Chain;

use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Framework\Base\Collection\Traits\AppendableInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\AppendManyTrait;
use LDL\Framework\Base\Collection\Traits\BeforeAppendInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\BeforeRemoveInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\CollectionInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\FilterByClassInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\FilterByInterfaceTrait;
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
    use BeforeRemoveInterfaceTrait;
    use RemovableInterfaceTrait;
    use TruncateInterfaceTrait {truncate as private _truncate;}
    use FilterByInterfaceTrait;
    use FilterByClassInterfaceTrait;

    /**
     * @var array
     */
    private $dumpable = [];

    /**
     * @var ValidatorChainInterface
     */
    private $successChain;

    /**
     * @var ValidatorChainInterface
     */
    private $errorChain;

    /**
     * @var ValidatorInterface
     */
    private $lastExecuted;

    public function __construct(iterable $validators=null)
    {
        $this->getBeforeAppend()->append(static function ($collection, $item, $key){
            (new InterfaceComplianceValidator(ValidatorInterface::class, true))->validate($item);
        });

        if(null !== $validators) {
            $this->appendMany($validators);
        }
    }

    public function validate($value, ...$params) : void
    {
        if(0 === $this->count){
            return;
        }

        /**
         * @var \Exception[]
         */
        $combinedException = new CombinedException();
        $this->successChain = new ValidatorChain();
        $this->errorChain = new ValidatorChain();
        $atLeastOneValid = false;

        /**
         * @var ValidatorInterface $validator
         */
        foreach($this as $validator){
            $this->lastExecuted = $validator;
            $isStrict = $validator instanceof HasValidatorConfigInterface ? $validator->getConfig()->isStrict() : true;

            if(true === $isStrict){
                $validator->validate($value, ...$params);
                $atLeastOneValid=true;
                $this->successChain->append($validator);
                continue;
            }

            try{
                $validator->validate($value, ...$params);
                $atLeastOneValid = true;
                $this->successChain->append($validator);
            }catch(\Exception $e){
                $combinedException->append($e);
                $this->errorChain->append($validator);
            }
        }

        if($atLeastOneValid){
            return;
        }

        throw $combinedException;
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

    public function getSuccededValidators() : ValidatorChainInterface
    {
        if(null === $this->successChain){
            $this->successChain = new ValidatorChain();
        }

        return $this->successChain;
    }

    public function getErrorValidators() : ValidatorChainInterface
    {
        if(null === $this->errorChain){
            $this->errorChain = new ValidatorChain();
        }

        return $this->errorChain;
    }

    public function getLastExecuted(): ?ValidatorInterface
    {
        return $this->lastExecuted;
    }
}