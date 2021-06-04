<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Config;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Framework\Base\Exception\ArrayFactoryException;
use LDL\Validators\Config\NegatedValidatorConfigInterface;
use LDL\Validators\Config\Traits\NegatedValidatorConfigTrait;
use LDL\Validators\Config\Traits\ValidatorConfigTrait;
use LDL\Validators\Config\ValidatorConfigInterface;

class ValidatorChainConfig implements ValidatorConfigInterface, NegatedValidatorConfigInterface
{
    use ValidatorConfigTrait;
    use NegatedValidatorConfigTrait;

    /**
     * @var string
     */
    private $operator;

    public function __construct(
        string $operator,
        bool $negated=false,
        bool $dumpable=true,
        string $description=null
    )
    {
        $this->operator = $operator;
        $this->_tDumpable = $dumpable;
        $this->_tNegated = $negated;
        $this->_tDescription = $description;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @param array $data
     * @return ArrayFactoryInterface
     * @throws ArrayFactoryException
     */
    public static function fromArray(array $data = []): ArrayFactoryInterface
    {
        if(false === array_key_exists('operator', $data)){
            $msg = sprintf("Missing property 'operator' in %s", __CLASS__);
            throw new ArrayFactoryException($msg);
        }

        return new self(
            (string) $data['operator'],
            array_key_exists('negated', $data) ? (bool)$data['negated'] : false,
            array_key_exists('dumpable', $data) ? (bool)$data['dumpable'] : true,
            array_key_exists('description', $data) ? (string)$data['description'] : null
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'operator' => $this->operator,
            'negated' => $this->_tNegated,
            'dumpable' => $this->_tDumpable,
            'description' => $this->_tDescription
        ];
    }
}