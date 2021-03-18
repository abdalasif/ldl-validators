<?php declare(strict_types=1);

namespace LDL\Validators\Chain;

use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Framework\Base\Collection\Traits\AppendableInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\CollectionInterfaceTrait;
use LDL\Framework\Base\Exception\LockingException;
use LDL\Framework\Base\Traits\LockableObjectInterfaceTrait;
use LDL\Type\Collection\Validator\Exception\ValidatorChainSoftValidationException;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\InterfaceComplianceValidator;
use LDL\Validators\ValidatorInterface;

class ValidatorChain implements ValidatorChainInterface
{
    use CollectionInterfaceTrait;
    use AppendableInterfaceTrait;
    use AppendableInterfaceTrait { append as private appendFromTrait; }
    use LockableObjectInterfaceTrait;

    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface
    {
        return new self;
    }

    public function getConfig()
    {

    }

    public function append($item, $key = null): CollectionInterface
    {
        if($this->isLocked()){
            throw new LockingException("Validator chain is locked, no items can be added");
        }

        (new InterfaceComplianceValidator(ValidatorInterface::class, true))->validate($item);

        return $this->appendFromTrait($item, $key);
    }

    public function validate($value) : void
    {
        if(0 === $this->count){
            return;
        }

        /**
         * @var \Exception[]
         */
        $exceptions = [];
        $atLeastOneValid = false;

        /**
         * @var ValidatorInterface $validator
         */
        foreach($this as $validator){
            if($validator->getConfig()->isStrict()){
                $validator->validate($value);
                $atLeastOneValid=true;
                continue;
            }

            try{
                $validator->validate($value);
                $atLeastOneValid = true;
            }catch(\Exception $e){
                $exceptions[] = $e;
            }
        }

        if($atLeastOneValid){
            return;
        }

        $messages = [];

        foreach($exceptions as $exception){
            $msg = sprintf('"%s": "%s"', get_class($exception), $exception->getMessage());
            $messages[] = $msg;
        }

        throw new ValidatorChainSoftValidationException(
            implode("\n", $messages),
            0,
            null,
            $exceptions
        );
    }

}