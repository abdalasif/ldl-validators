<?php declare(strict_types=1);

namespace LDL\Validators\Chain;

use LDL\Validators\Chain\Dumper\ValidatorChainExprDumper;
use LDL\Validators\Chain\Exception\CombinedException;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Traits\ValidatorValidateTrait;
use LDL\Validators\ValidatorInterface;

class OrValidatorChain extends AbstractValidatorChain
{
    use ValidatorValidateTrait;

    public const OPERATOR = ' || ';

    public function assertTrue($value, ...$params): void
    {
        $this->reset();
        $combinedException = new CombinedException();

        /**
         * @var ValidatorInterface $validator
         */
        foreach($this as $validator){
            $this->lastExecuted = $validator;

            try {
                $validator->validate($value, ...$params);
                $this->succeeded[] = $validator;
                break;
            }catch(\Exception $e){
                $this->failed[] = $validator;
                $combinedException->append($e);
            }
        }

        if(!count($this->succeeded)){
            throw $combinedException;
        }
    }

    public function assertFalse($value, ...$params): void
    {
        $this->reset();
        $combinedException = new CombinedException();

        /**
         * @var ValidatorInterface $validator
         */
        foreach($this as $validator){
            $this->lastExecuted = $validator;

            try {
                $validator->validate($value, ...$params);
                $this->succeeded[] = $validator;
                break;
            }catch(\Exception $e){
                $this->failed[] = $validator;
                $combinedException->append($e);
            }
        }

        if(count($this->succeeded)){
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
        iterable $validators=null
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

        return self::factory(
            $validators,
            $config->isDumpable(),
            $config->isNegated(),
            $config->getDescription()
        );
    }

    private function reset(): void
    {
        $this->lastExecuted = null;
        $this->failed = [];
        $this->succeeded = [];

        if(0 === $this->count()){
            return;
        }
    }
}
