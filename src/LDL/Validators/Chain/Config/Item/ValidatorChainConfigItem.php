<?php declare(strict_types=1);

namespace LDL\Validators\Collection\Validator\Chain\Config\Item;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Validators\Collection\Interfaces\Validation\ValidatorInterface;
use LDL\Validators\Collection\Types\Scalar\Validator\ScalarValidator;
use LDL\Validators\Collection\Validator\Config\ValidatorConfigInterface;
use LDL\Validators\Exception\TypeMismatchException;

class ValidatorChainConfigItem implements ValidatorChainConfigItemInterface
{

    /**
     * @var mixed (scalar)
     */
    private $key;

    /**
     * @var string
     */
    private $class;

    /**
     * @var ValidatorConfigInterface
     */
    private $config;

    public function __construct($key, string $class, ValidatorConfigInterface $config)
    {
        if(!is_string($key) && !is_numeric($key)){
            throw new \InvalidArgumentException(
                sprintf(
                '$key argument must be a string or a numeric type, "%s" was given',
                    gettype($key)
                )
            );
        }

        if(!class_exists($class)){
            throw new \InvalidArgumentException("Class: \"$class\" does not exists, check your autoloader.");
        }

        $this->key = $key;
        $this->class = $class;
        $this->config = $config;
    }

    public static function fromArray(array $data = []): ArrayFactoryInterface
    {
        if(false === array_key_exists('validator', $data)){
            throw new \InvalidArgumentException("Expected 'validator' option");
        }

        if(!is_string($data['validator'])){
            throw new \InvalidArgumentException(
                sprintf(
                    '\'class\' index must be a string, "%s" was given',
                    gettype($data['validator'])
                )
            );
        }

        if(false === array_key_exists('config', $data)){
            throw new \InvalidArgumentException("Expected 'config' option");
        }

        if(!is_array($data['config'])){
            throw new \InvalidArgumentException('config option is expected to be an array');
        }

        if(!array_key_exists('class', $data['config'])){
            throw new \InvalidArgumentException('Missing \'class\' index in config array');
        }

        if(!is_string($data['config']['class'])){
            throw new \InvalidArgumentException(
                sprintf(
                    '\'class\' index must be a string, "%s" was given',
                    gettype($data['config']['class'])
                )
            );
        }

        if(!class_exists($data['config']['class'])){
            throw new \InvalidArgumentException(sprintf('Class %s does not exists', $data['class']));
        }

        if(!is_array($data['config']['config'])){
            throw new \InvalidArgumentException(
                sprintf(
                    'config[\'config\'] option is expected to be an array, "%s" was given',
                    gettype($data['config']['config'])
                )
            );
        }

        if(false === array_key_exists('key', $data)){
            throw new \InvalidArgumentException("Expected 'key' option");
        }

        $configClass = $data['config']['class'];

        return new self(
            $data['key'],
            $data['validator'],
            $configClass::fromArray($data['config']['config'])
        );
    }

    public function getValidatorInstance() : ValidatorInterface
    {
        /**
         * @var ValidatorInterface $class
         */
        $class = $this->class;

        return $class::fromConfig($this->config);
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'validator' => $this->class,
            'config' => [
                'class' => get_class($this->config),
                'config' => $this->config->toArray()
            ]
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return string|number
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return ValidatorConfigInterface
     */
    public function getConfig(): ValidatorConfigInterface
    {
        return $this->config;
    }
}