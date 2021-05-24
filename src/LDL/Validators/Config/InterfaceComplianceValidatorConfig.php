<?php declare(strict_types=1);

namespace LDL\Validators\Config;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Framework\Base\Exception\ArrayFactoryException;
use LDL\Validators\Config\Traits\ValidatorConfigTrait;

class InterfaceComplianceValidatorConfig implements ValidatorConfigInterface
{
    use ValidatorConfigTrait;

    /**
     * @var string
     */
    private $interface;

    public function __construct(
        string $interface,
        bool $negated=false,
        bool $dumpable=true,
        string $description=null
    )
    {
        if(!interface_exists($interface)){
            throw new \LogicException("$interface interface does not exists");
        }

        $this->interface = $interface;
        $this->_tNegated = $negated;
        $this->_tDumpable = $dumpable;
        $this->_tDescription = $description;
    }

    /**
     * @return string
     */
    public function getInterface(): string
    {
        return $this->interface;
    }

    /**
     * @param array $data
     * @return ArrayFactoryInterface
     * @throws ArrayFactoryException
     */
    public static function fromArray(array $data = []): ArrayFactoryInterface
    {
        if(false === array_key_exists('interface', $data)){
            $msg = sprintf("Missing property 'interface' in %s", __CLASS__);
            throw new ArrayFactoryException($msg);
        }

        try{
            return new self(
                (string) $data['interface'],
                array_key_exists('negated', $data) ? (bool)$data['negated'] : false,
                array_key_exists('dumpable', $data) ? (bool)$data['dumpable'] : true
            );
        }catch(\Exception $e){
            throw new ArrayFactoryException($e->getMessage());
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'interface' => $this->interface,
            'negated' => $this->_tNegated,
            'dumpable' => $this->_tDumpable
        ];
    }
}