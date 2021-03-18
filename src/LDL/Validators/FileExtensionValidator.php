<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\Exception\InvalidConfigException;
use LDL\Validators\Config\FileExtensionValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;

class FileExtensionValidator implements ValidatorInterface
{
    /**
     * @var FileExtensionValidatorConfig
     */
    private $config;

    public function __construct(string $extension, bool $strict = true)
    {
        $this->config = new FileExtensionValidatorConfig($extension, $strict);
    }

    /**
     * @param mixed $value
     */
    public function validate($value): void
    {
        if($value === $this->config->getExtension()){
            return;
        }

        throw new \LogicException('Extension does not match');
    }

    /**
     * @param ValidatorConfigInterface $config
     * @return FileExtensionValidator
     * @throws InvalidConfigException
     */
    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface
    {
        if(false === $config instanceof FileExtensionValidatorConfig){
            $msg = sprintf(
                'Config expected to be %s, config of class %s was given',
                __CLASS__,
                get_class($config)
            );
            throw new InvalidConfigException($msg);
        }

        /**
         * @var FileExtensionValidatorConfig $config
         */
        return new self($config->getExtension(), $config->isStrict());
    }

    /**
     * @return FileExtensionValidatorConfig
     */
    public function getConfig(): FileExtensionValidatorConfig
    {
        return $this->config;
    }
}