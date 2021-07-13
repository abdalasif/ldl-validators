<?php declare(strict_types=1);

namespace LDL\Validators\Chain;

use LDL\Validators\Chain\Dumper\FilterDumpableInterface;
use LDL\Validators\Chain\Dumper\ValidatorChainExprDumper;
use LDL\Validators\Chain\Exception\CombinedException;
use LDL\Validators\Chain\Item\ValidatorChainItemInterface;
use LDL\Validators\Chain\Traits\FilterDumpableInterfaceTrait;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\NegatedValidatorInterface;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class OrValidatorChain extends AbstractValidatorChain implements NegatedValidatorInterface, FilterDumpableInterface
{
    use ValidatorValidateTrait;
    use FilterDumpableInterfaceTrait;
    use NegatedValidatorTrait;

    public const OPERATOR = ' || ';

    public function __construct(
        iterable $validators=null,
        string $description=null,
        bool $negated = null
    )
    {
        parent::__construct($validators, $description);
        $this->_tNegated = $negated ?? false;
    }

    public function assertTrue($value, ...$params): void
    {
        $this->reset();

        if(0 === $this->count()){
            return;
        }

        $combinedException = new CombinedException();

        /**
         * @var ValidatorChainItemInterface $chainItem
         */
        foreach($this as $chainItem){
            $this->setLastExecuted($chainItem);

            try {
                $chainItem->getValidator()->validate($value, ...$params);
                $this->getSucceeded()->append($chainItem->getValidator());
                break;
            }catch(\Exception $e){
                $this->getFailed()->append($chainItem->getValidator());
                $combinedException->append($e);
            }
        }

        if(!$this->getSucceeded()->count()){
            throw $combinedException;
        }
    }

    public function assertFalse($value, ...$params): void
    {
        $this->reset();

        if(0 === $this->count()){
            return;
        }

        $combinedException = new CombinedException();

        /**
         * @var ValidatorChainItemInterface $chainItem
         */
        foreach($this as $chainItem){
            $this->setLastExecuted($chainItem);

            try {
                $chainItem->getValidator()->validate($value, ...$params);
                $this->getSucceeded()->append($chainItem->getValidator());
                break;
            }catch(\Exception $e){
                $this->getFailed()->append($chainItem->getValidator());
                $combinedException->append($e);
            }
        }

        if($this->getSucceeded()->count()){
            $combinedException->append(
                new \LogicException(
                    sprintf(
                        'Failed to assert that value "%s" complies to: %s',
                        var_export($value, true),
                        ValidatorChainExprDumper::dump($this)
                    )
                )
            );

            throw $combinedException;
        }
    }

    public static function fromConfig(
        ValidatorConfigInterface $config,
        iterable $validators=null,
        string $description=null,
        bool $negated = null
    ): ValidatorChainInterface
    {
        if(!$config instanceof Config\ValidatorChainConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new \InvalidArgumentException($msg);
        }

        return new self(
            $validators,
            $description,
            $negated
        );
    }
}
