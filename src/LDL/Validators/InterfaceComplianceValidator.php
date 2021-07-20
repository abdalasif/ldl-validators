<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Exception\TypeMismatchException;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class InterfaceComplianceValidator implements ValidatorInterface, NegatedValidatorInterface, ValidatorHasConfigInterface
{
    use ValidatorValidateTrait {validate as _validate;}
    use NegatedValidatorTrait;

    /**
     * @var string
     */
    private $interface;

    /**
     * @var string
     */
    private $description;

    public function __construct(string $interface, bool $negated=false, string $description=null)
    {
        if(!interface_exists($interface)){
            throw new \LogicException("$interface interface does not exists");
        }

        $this->interface = $interface;
        $this->_tNegated = $negated;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getInterface(): string
    {
        return $this->interface;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        if(!$this->description){
            return sprintf(
                'Validate that a given class implements: %s',
                $this->interface
            );
        }

        return $this->description;
    }

    /**
     * @param mixed $value
     * @throws TypeMismatchException
     */
    public function validate($value) : void
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

    public function assertTrue($value): void
    {
        if($value instanceof $this->interface) {
            return;
        }

        $msg = sprintf(
            'Value of class "%s", does not complies to interface: "%s"',
            get_class($value),
            $this->interface
        );

        throw new TypeMismatchException($msg);
    }

    public function assertFalse($value): void
    {
        if(!$value instanceof $this->interface) {
            return;
        }

        $msg = sprintf(
            'Value of class "%s", must NOT comply to interface: "%s"',
            get_class($value),
            $this->interface
        );

        throw new TypeMismatchException($msg);
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
        if(false === array_key_exists('interface', $data)){
            $msg = sprintf("Missing property 'interface' in %s", __CLASS__);
            throw new Exception\TypeMismatchException($msg);
        }

        try{
            return new self(
                (string) $data['interface'],
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
            'interface' => $this->interface,
            'negated' => $this->_tNegated,
            'description' => $this->getDescription()
        ];
    }
}