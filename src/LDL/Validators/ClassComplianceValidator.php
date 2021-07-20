<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Exception\TypeMismatchException;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class ClassComplianceValidator implements ValidatorInterface, NegatedValidatorInterface, ValidatorHasConfigInterface
{
    use ValidatorValidateTrait {validate as _validate;}
    use NegatedValidatorTrait;

    /**
     * @var string
     */
    private $class;

    /**
     * @var boolean
     */
    private $strict;

    /**
     * @var string
     */
    private $description;

    public function __construct(
        string $class,
        bool $strict=false,
        bool $negated=false,
        string $description = null
    )
    {
        if(!class_exists($class)){
            throw new \LogicException("Class \"$class\" does not exists");
        }

        $this->class = $class;
        $this->strict = $strict;
        $this->description = $description;
        $this->_tNegated = $negated;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return bool
     */
    public function isStrict(): bool
    {
        return $this->strict;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        if(!$this->description){
            return sprintf(
                'Validate that a given class complies with class: %s in %s mode',
                $this->class,
                $this->strict ? 'strict' : 'non-strict'
            );
        }

        return $this->description;
    }

    /**
     * @param mixed $value
     * @throws TypeMismatchException
     */
    public function validate($value): void
    {
        if(!is_object($value)){
            $msg = sprintf(
                'Value expected for "%s", must be an Object, "%s" was given',
                __CLASS__,
                gettype($value)
            );
            throw new TypeMismatchException($msg);
        }

        $this->_validate($value);
    }

    public function assertTrue($value) : void
    {
        if($this->compare($value)){
            return;
        }

        $msg = sprintf(
            'Value of class "%s", does not complies to class: "%s"',
            get_class($value),
            $this->class
        );

        throw new TypeMismatchException($msg);
    }

    public function assertFalse($value) : void
    {
        if(!$this->compare($value)){
            return;
        }

        $msg = sprintf(
            'Value of class "%s", can NOT be of class: "%s"',
            get_class($value),
            $this->class
        );

        throw new TypeMismatchException($msg);
    }

    private function compare($value)
    {
        $class = $this->class;
        return $this->strict ? get_class($value) === $class : $value instanceof $class;
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
        if(false === array_key_exists('class', $data)){
            $msg = sprintf("Missing property 'class' in %s", __CLASS__);
            throw new Exception\TypeMismatchException($msg);
        }

        try{
            return new self(
                (string) $data['class'],
                array_key_exists('strict', $data) ? (bool)$data['strict'] : false,
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
            'class' => $this->class,
            'strict' => $this->strict,
            'negated' => $this->_tNegated,
            'description' => $this->getDescription()
        ];
    }
}