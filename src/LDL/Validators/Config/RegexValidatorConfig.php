<?php declare(strict_types=1);

namespace LDL\Validators\Config;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Framework\Base\Exception\ArrayFactoryException;
use LDL\Framework\Base\Helper\RegexHelper;

class RegexValidatorConfig implements ValidatorConfigInterface
{
    use ValidatorConfigInterfaceTrait;

    /**
     * @var string
     */
    private $regex;

    public function __construct(string $regex, bool $strict=false)
    {
        RegexHelper::validate($regex);

        $this->regex = $regex;
        $this->_isStrict = $strict;
    }

    /**
     * @return string
     */
    public function getRegex(): string
    {
        return $this->regex;
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
     * @return ArrayFactoryInterface
     * @throws ArrayFactoryException
     */
    public static function fromArray(array $data = []): ArrayFactoryInterface
    {
        if(false === array_key_exists('regex', $data)){
            $msg = sprintf("Missing property 'regex' in %s", __CLASS__);
            throw new ArrayFactoryException($msg);
        }

        try{
            return new self((string) $data['regex'], array_key_exists('strict', $data) ? (bool)$data['strict'] : false);
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
            'regex' => $this->regex,
            'strict' => $this->_isStrict
        ];
    }
}