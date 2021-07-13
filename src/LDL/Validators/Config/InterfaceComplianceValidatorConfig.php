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
        string $interface
    )
    {
        if(!interface_exists($interface)){
            throw new \LogicException("$interface interface does not exists");
        }

        $this->interface = $interface;
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
                (string) $data['interface']
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
            'interface' => $this->interface
        ];
    }
}