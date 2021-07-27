<?php declare(strict_types=1);

namespace LDL\Validators\Chain;

use LDL\Validators\Chain\Dumper\ValidatorChainExprDumper;
use LDL\Validators\Chain\Exception\CombinedException;
use LDL\Validators\Chain\Item\ValidatorChainItemInterface;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class OrValidatorChain extends AbstractValidatorChain implements BooleanValidatorChainInterface
{
    use ValidatorValidateTrait;
    use NegatedValidatorTrait;

    private const OPERATOR = ' || ';

    public function __construct(
        iterable $validators=null,
        string $description=null,
        bool $negated = null
    )
    {
        parent::__construct($validators, $description);
        $this->_tNegated = $negated ?? false;
    }

    public function getOperator(): string
    {
        return self::OPERATOR;
    }

    public function assertTrue($value, ...$params): void
    {
        $chainItems = $this->getChainItems();
        $chainItems->getValidators()->onBeforeValidate(...$params);
        $this->onBeforeValidate()->call(...$params);

        if(0 === $chainItems->count()){
            return;
        }

        $combinedException = new CombinedException();

        /**
         * @var ValidatorChainItemInterface $chainItem
         */
        foreach($chainItems as $chainItem){
            $this->setLastExecuted($chainItem);

            $validator = $chainItem->getValidator();

            try {
                $validator->validate($value, ...$params);
                $this->getSucceeded()->append($chainItem);
                break;
            }catch(\Exception $e){
                $this->getFailed()->append($chainItem);
                $combinedException->append($e);
            }
        }

        if(!$this->getSucceeded()->count()){
            throw $combinedException;
        }
    }

    public function assertFalse($value, ...$params): void
    {
        $chainItems = $this->getChainItems();
        $chainItems->getValidators()->onBeforeValidate(...$params);
        $this->onBeforeValidate()->call(...$params);

        if(0 === $chainItems->count()){
            return;
        }

        $combinedException = new CombinedException();

        /**
         * @var ValidatorChainItemInterface $chainItem
         */
        foreach($chainItems as $chainItem){
            $this->setLastExecuted($chainItem);

            $validator = $chainItem->getValidator();

            try {
                $validator->validate($value, ...$params);
                $this->getSucceeded()->append($chainItem);
                break;
            }catch(\Exception $e){
                $this->getFailed()->append($chainItem);
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
