<?php declare(strict_types=1);

namespace LDL\Validators\Config;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Framework\Base\Exception\ArrayFactoryException;
use LDL\Framework\Helper\RegexHelper;
use LDL\Validators\Config\Traits\ValidatorConfigTrait;

class RegexValidatorConfig implements ValidatorConfigInterface
{
    use ValidatorConfigTrait;

    /**
     * @var string
     */
    private $regex;

    public function __construct(
        string $regex
    )
    {
        RegexHelper::validate($regex);

        $this->regex = $regex;
    }

    /**
     * @return string
     */
    public function getRegex(): string
    {
        return $this->regex;
    }

    /**
     * @param array $data
     * @return ValidatorConfigInterface
     * @throws ArrayFactoryException
     */
    public static function fromArray(array $data = []): ArrayFactoryInterface
    {
        if(false === array_key_exists('regex', $data)){
            $msg = sprintf("Missing property 'regex' in %s", __CLASS__);
            throw new ArrayFactoryException($msg);
        }

        try{
            return new self(
                (string) $data['regex']
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
            'regex' => $this->regex
        ];
    }
}