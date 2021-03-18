<?php declare(strict_types=1);

namespace LDL\Validators\Config;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Framework\Base\Exception\ArrayFactoryException;

class ExactFileNameValidatorConfig implements ValidatorConfigInterface
{
    use ValidatorConfigInterfaceTrait;

    /**
     * @var string
     */
    private $name;

    public function __construct(string $name, bool $strict=true)
    {
        $this->name = $name;
        $this->_isStrict = $strict;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function jsonSerialize() : array
    {
        return $this->toArray();
    }

    /**
     * @param array $data
     * @return ValidatorConfigInterface
     * @throws ArrayFactoryException
     */
    public static function fromArray(array $data = []): ArrayFactoryInterface
    {
        if(false === array_key_exists('name', $data)){
            $msg = sprintf("Missing property 'name' in %s", __CLASS__);
            throw new ArrayFactoryException($msg);
        }

        try{
            return new self((string) $data['name'], array_key_exists('strict', $data) ? (bool)$data['strict'] : true);
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
            'name' => $this->name,
            'strict' => $this->_isStrict
        ];
    }
}