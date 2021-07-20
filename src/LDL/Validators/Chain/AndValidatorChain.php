<?php declare(strict_types=1);

namespace LDL\Validators\Chain;

use LDL\Validators\Chain\Dumper\FilterDumpableInterface;
use LDL\Validators\Chain\Dumper\ValidatorChainExprDumper;
use LDL\Validators\Chain\Item\ValidatorChainItemInterface;
use LDL\Validators\Chain\Traits\FilterDumpableInterfaceTrait;
use LDL\Validators\NegatedValidatorInterface;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;
use LDL\Validators\ValidatorHasConfigInterface;

class AndValidatorChain extends AbstractValidatorChain implements ValidatorHasConfigInterface, NegatedValidatorInterface, FilterDumpableInterface
{
    use ValidatorValidateTrait;
    use FilterDumpableInterfaceTrait;
    use NegatedValidatorTrait;

    public const OPERATOR = ' && ';

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

        /**
         * @var ValidatorChainItemInterface $chainItem
         */
        foreach($this as $chainItem){
            $this->setLastExecuted($chainItem);

            try {
                $chainItem->getValidator()->validate($value, ...$params);
                $this->getSucceeded()->append($chainItem->getValidator());
            }catch(\Exception $e){
                $this->getFailed()->append($chainItem->getValidator());
                throw $e;
            }
        }

    }

    public function assertFalse($value, ...$params): void
    {
        $this->reset();

        if(0 === $this->count()){
            return;
        }

        /**
         * @var ValidatorChainItemInterface $chainItem
         */
        foreach($this as $chainItem){
            $this->setLastExecuted($chainItem);

            try {
                $chainItem->getValidator()->validate($value, ...$params);
                $this->getSucceeded()->append($chainItem->getValidator());
            }catch(\Exception $e){
                $this->getFailed()->append($chainItem->getValidator());
                break;
            }
        }

        if($this->getFailed()->count() > 0){
            return;
        }

        throw new \LogicException(
            sprintf(
                'Failed to assert that value "%s" complies to: %s',
                var_export($value, true),
                ValidatorChainExprDumper::dump($this)
            )
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
}
