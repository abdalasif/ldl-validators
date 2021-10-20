<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Framework\Base\Contracts\Type\ToStringInterface;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\Traits\ValidatorDescriptionTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class StringEqualsValidator implements ValidatorInterface, NegatedValidatorInterface, ValidatorHasConfigInterface
{
    use ValidatorValidateTrait;
    use NegatedValidatorTrait;
    use ValidatorDescriptionTrait;

    private const DESCRIPTION = 'Validates equality between strings';

    /**
     * @var string
     */
    private $value;

    /**
     * @var bool
     */
    private $strict;

    public function __construct(
        string $name,
        bool $strict=true,
        bool $negated=false,
        string $description=null
    )
    {
        $this->value = $name;
        $this->strict = $strict;
        $this->_tNegated = $negated;
        $this->_tDescription = $description ?? self::DESCRIPTION;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isStrict() : bool
    {
        return $this->strict;
    }

    public function assertTrue($value): void
    {
        if($value instanceof ToStringInterface) {
            $value = $value->toString();
        }

        $comparison = $this->strict ? $this->value === $value : $this->value == $value;

        if($comparison){
            return;
        }

        throw new \LogicException(
            sprintf(
                'Given value "%s" is not%sequal to %s',
                is_scalar($value) ? var_export($value, true) : gettype($value),
                $this->strict ? ' strictly ' : ' ',
                $this->value
            )
        );
    }

    public function assertFalse($value): void
    {
        if($value instanceof ToStringInterface) {
            $value = $value->toString();
        }

        $comparison = $this->strict ? $this->value === $value : $this->value == $value;

        if(!$comparison){
            return;
        }

        throw new \LogicException(
            sprintf(
                'Given value "%s" must NOT be%sequal to "%s"',
                is_scalar($value) ? var_export($value, true) : gettype($value),
                $this->strict ? ' strictly ' : ' ',
                $this->value
            )
        );
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
        if(false === array_key_exists('value', $data)){
            $msg = sprintf("Missing property 'value' in %s", __CLASS__);
            throw new Exception\TypeMismatchException($msg);
        }

        try{
            return new self(
                (string) $data['value'],
                array_key_exists('strict', $data) ? (bool)$data['strict'] : true,
                array_key_exists('negated', $data) ? (bool)$data['negated'] : false,
                $data['description'] ?? null
            );
        }catch(\Exception $e){
            throw new Exception\TypeMismatchException($e->getMessage());
        }
    }

    public function getConfig(): array
    {
        return [
            'value' => $this->value,
            'strict' => $this->strict,
            'negated' => $this->_tNegated,
            'description' => $this->getDescription()
        ];
    }
}