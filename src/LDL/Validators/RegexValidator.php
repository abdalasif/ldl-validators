<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Framework\Base\Contracts\Type\ToStringInterface;
use LDL\Framework\Helper\RegexHelper;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class RegexValidator implements ValidatorInterface, NegatedValidatorInterface, ValidatorHasConfigInterface
{
    use ValidatorValidateTrait {validate as _validate;}
    use NegatedValidatorTrait;

    /**
     * @var string
     */
    private $regex;

    /**
     * @var string
     */
    private $description;

    public function __construct(string $regex, bool $negated=false, string $description=null)
    {
        RegexHelper::validate($regex);

        $this->regex = $regex;
        $this->_tNegated = $negated;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getRegex(): string
    {
        return $this->regex;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        if(!$this->description){
            return sprintf(
                'Validate regex with pattern: %s',
                $this->regex,
            );
        }

        return $this->description;
    }

    public function validate($value): void
    {
        if($value instanceof ToStringInterface){
            $value = $value->toString();
        }

        if(!is_scalar($value)){
            throw new \LogicException(sprintf('Validator %s only accepts scalar values', __CLASS__));
        }

        $this->_validate($value);
    }

    public function assertTrue($value): void
    {
        if(preg_match($this->regex, (string) $value)) {
            return;
        }

        $msg = "Given value: \"$value\" does not matches regex: \"{$this->regex}\"";
        throw new Exception\RegexValidatorException($msg);
    }

    public function assertFalse($value): void
    {
        if(!preg_match($this->regex, (string) $value)) {
            return;
        }

        $msg = "Given value: \"$value\" matches regex: \"{$this->regex}\"";
        throw new Exception\RegexValidatorException($msg);
    }

    public function jsonSerialize(): array
    {
        return $this->getConfig();
    }

    /**
     * @param array $data
     * @return ValidatorInterface
     * @throws Exception\TypeMismatchException
     */
    public static function fromConfig(array $data = []): ValidatorInterface
    {
        if(!array_key_exists('regex', $data)){
            $msg = sprintf("Missing property 'value' in %s", __CLASS__);
            throw new Exception\TypeMismatchException($msg);
        }

        try{
            return new self(
                (string) $data['regex'],
                array_key_exists('negated', $data) ? (bool)$data['negated'] : false,
                $data['description'] ?? null
            );
        }catch(\Exception $e){
            throw new Exception\TypeMismatchException($e->getMessage());
        }
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'regex' => $this->regex,
            'negated' => $this->_tNegated,
            'description' => $this->getDescription()
        ];
    }
}