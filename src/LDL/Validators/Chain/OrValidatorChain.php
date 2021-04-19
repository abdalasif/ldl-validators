<?php declare(strict_types=1);

namespace LDL\Validators\Chain;

use LDL\Validators\Chain\Exception\CombinedException;
use LDL\Validators\ValidatorInterface;

class OrValidatorChain extends AbstractValidatorChain
{
    public const OPERATOR = ' || ';

    public function validate($value, ...$params) : void
    {
        $this->config->isNegated() ? $this->assertFalse($value, ...$params) : $this->assertTrue($value, ...$params);
    }

    public function assertTrue($value, ...$params): void
    {
        $this->lastExecuted = null;
        $this->failed = [];
        $this->succeeded = [];

        if(0 === $this->count()){
            return;
        }

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
        $this->lastExecuted = null;
        $this->failed = [];
        $this->succeeded = [];

        if(0 === $this->count()){
            return;
        }

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
            throw $combinedException;
        }
    }
}
