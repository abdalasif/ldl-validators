<?php declare(strict_types=1);

namespace LDL\Validators\Chain;

use LDL\Validators\Chain\Dumper\FilterDumpableInterface;
use LDL\Validators\Chain\Dumper\ValidatorChainExprDumper;
use LDL\Validators\Chain\Exception\CombinedException;
use LDL\Validators\Chain\Item\ValidatorChainItemInterface;
use LDL\Validators\Chain\Traits\FilterDumpableInterfaceTrait;
use LDL\Validators\NegatedValidatorInterface;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;
use LDL\Validators\ValidatorHasConfigInterface;

class OrValidatorChain extends AbstractValidatorChain implements ValidatorHasConfigInterface, NegatedValidatorInterface, FilterDumpableInterface
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

            $validator = $chainItem->getValidator();

            try {
                $validator->validate($value, ...$params);
                $this->getSucceeded()->append($validator);
                break;
            }catch(\Exception $e){
                $this->getFailed()->append($validator);
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

            $validator = $chainItem->getValidator();

            try {
                $validator->validate($value, ...$params);
                $this->getSucceeded()->append($validator);
                break;
            }catch(\Exception $e){
                $this->getFailed()->append($validator);
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

    public function jsonSerialize(): array
    {
        return $this->getConfig();
    }

    public static function fromConfig(
        iterable $validators=null,
        string $description=null,
        bool $negated = null
    ): ValidatorChainInterface
    {
        return new self(
            $validators,
            $description,
            $negated
        );
    }

    public function getConfig(): array
    {
        return [
            'operator' => self::OPERATOR
        ];
    }
}
