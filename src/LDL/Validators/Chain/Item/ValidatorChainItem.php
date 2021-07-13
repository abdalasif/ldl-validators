<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Item;

use LDL\Validators\ValidatorInterface;

class ValidatorChainItem implements ValidatorChainItemInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var bool
     */
    private $dumpable;

    public function __construct(ValidatorInterface $validator, bool $dumpable=false){
        $this->validator = $validator;
        $this->dumpable = $dumpable;
    }

    public function isDumpable() : bool
    {
        return $this->dumpable;
    }

    public function getValidator() : ValidatorInterface
    {
        return $this->validator;
    }
}