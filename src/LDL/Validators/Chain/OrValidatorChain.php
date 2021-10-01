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
    private const DESCRIPTION = 'OR validator chain';

    public function __construct(
        iterable $validators=null,
        string $description=null,
        bool $negated = null
    )
    {
        parent::__construct($description ?? self::DESCRIPTION, $validators);
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

        $exceptions = [];

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
                $exceptions[] = $e;
            }
        }

        if($this->getSucceeded()->count() > 0 ) {
            return;
        }

        throw new CombinedException(
            sprintf(
                'Value: "%s" does not comply to validation: [%s]',
                $this->getVarType($value),
                ValidatorChainExprDumper::dump($this)
            ),
            0,
            null,
            $exceptions
        );
    }

    public function assertFalse($value, ...$params): void
    {
        $chainItems = $this->getChainItems();
        $chainItems->getValidators()->onBeforeValidate(...$params);
        $this->onBeforeValidate()->call(...$params);

        if(0 === $chainItems->count()){
            return;
        }

        $exceptions = [];

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
                $exceptions[] = $e;
            }
        }

        if(!$this->getSucceeded()->count()) {
            return;
        }

        throw new CombinedException(
            sprintf(
                'Value: "%s" complies to validation: [%s]',
                $this->getVarType($value),
                ValidatorChainExprDumper::dump($this)
            ),
            0,
            null,
            $exceptions
        );
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

    private function getVarType($var) : string
    {
        if(is_object($var)){
            return get_class($var);
        }

        if(is_scalar($var)){
           return var_export($var, true);
        }

        return gettype($var);
    }
}
