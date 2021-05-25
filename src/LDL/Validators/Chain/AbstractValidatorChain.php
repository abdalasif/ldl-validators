<?php declare(strict_types=1);

namespace LDL\Validators\Chain;

use LDL\Framework\Base\Collection\Traits\AppendableInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\AppendManyTrait;
use LDL\Framework\Base\Collection\Traits\BeforeAppendInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\BeforeRemoveInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\CollectionInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\FilterByClassInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\FilterByInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\LockAppendInterfaceTrait;
use LDL\Framework\Base\Collection\Traits\RemovableInterfaceTrait;
use LDL\Framework\Base\Traits\LockableObjectInterfaceTrait;
use LDL\Validators\Chain\Config\ValidatorChainConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\InterfaceComplianceValidator;
use LDL\Validators\ValidatorInterface;

abstract class AbstractValidatorChain implements ValidatorChainInterface
{
    use CollectionInterfaceTrait;
    use LockableObjectInterfaceTrait;
    use BeforeAppendInterfaceTrait;
    use AppendableInterfaceTrait;
    use AppendManyTrait;
    use LockAppendInterfaceTrait;
    use BeforeRemoveInterfaceTrait;
    use RemovableInterfaceTrait;
    use FilterByInterfaceTrait;
    use FilterByClassInterfaceTrait;

    /**
     * @var array
     */
    protected $succeeded = [];

    /**
     * @var array
     */
    protected $failed = [];

    /**
     * @var ValidatorInterface
     */
    protected $lastExecuted;

    /**
     * @var Config\ValidatorChainConfig
     */
    protected $config;

    public function __construct(
        iterable $validators=null,
        bool $negated = false,
        bool $dumpable = true,
        string $description=null
    )
    {
        $this->getBeforeAppend()->append(static function ($collection, $item, $key){
            (new InterfaceComplianceValidator(ValidatorInterface::class))->validate($item);
        });

        if(null !== $validators) {
            $this->appendMany($validators, false);
        }

        $this->config = new Config\ValidatorChainConfig(static::OPERATOR, $negated, $dumpable, $description);
    }

    public static function factory(iterable $validators=null, ...$params) : ValidatorChainInterface
    {
        return new static($validators, ...$params);
    }

    /**
     * @return ValidatorChainInterface
     * @throws \Exception
     */
    public function filterDumpableItems(): ValidatorChainInterface
    {
        $self = $this->getEmptyInstance();

        /**
         * @var ValidatorInterface $validator
         */
        foreach($this as $key => $validator){
            if($validator->getConfig()->isDumpable()){
                $self->append($validator, $key);
            }
        }

        return $self;
    }

    public function getSucceeded() : ValidatorChainInterface
    {
        return new static($this->succeeded);
    }

    public function getFailed() : ValidatorChainInterface
    {
        return new static($this->failed);
    }

    public function getLastExecuted(): ?ValidatorInterface
    {
        return $this->lastExecuted;
    }

    public function getConfig() : ValidatorChainConfig
    {
        return $this->config;
    }

}
