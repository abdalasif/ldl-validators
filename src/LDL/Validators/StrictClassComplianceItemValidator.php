<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\Exception\InvalidConfigException;
use LDL\Validators\Config\StrictClassComplianceItemValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Exception\TypeMismatchException;

class StrictClassComplianceItemValidator implements ValidatorInterface, HasValidatorConfigInterface
{
    /**
     * @var StrictClassComplianceItemValidatorConfig
     */
    private $config;

    public function __construct(string $class, bool $strict=true)
    {
        $this->config = new StrictClassComplianceItemValidatorConfig($class, $strict);
    }

    /**
     * @param mixed $value
     * @throws TypeMismatchException
     */
    public function validate($value): void
    {
        if(!is_object($value)){
            $msg = sprintf(
                'Value expected for "%s", must be an Object, %s was given',
                __CLASS__,
                gettype($value)
            );
            throw new TypeMismatchException($msg);
        }

        $itemClass = get_class($value);

        if($itemClass === $this->config->getClass()) {
            return;
        }

        $msg = sprintf(
            'Item of class "%s", must be *exactly* an object of class: "%s"',
            get_class($value),
            $this->config->getClass()
        );

        throw new TypeMismatchException($msg);
    }

    /**
     * @param ValidatorConfigInterface $config
     * @return ValidatorInterface
     * @throws InvalidConfigException
     */
    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface
    {
        if(false === $config instanceof StrictClassComplianceItemValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new InvalidConfigException($msg);
        }

        /**
         * @var StrictClassComplianceItemValidatorConfig $config
         */
        return new self($config->getClass(), $config->isStrict());
    }

    /**
     * @return StrictClassComplianceItemValidatorConfig
     */
    public function getConfig(): StrictClassComplianceItemValidatorConfig
    {
        return $this->config;
    }
}