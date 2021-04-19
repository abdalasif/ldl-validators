<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\Exception\ValidatorException;

interface ValidatorInterface
{
    /**
     * The validate method is a simplification of assertTrue and assertFalse, it must validate accordingly to
     * the validator configuration. If the configuration is negated, the assertFalse method must be called, while if it
     * is not negated the assertTrue method must be called
     *
     * @param mixed $value
     * @throws \Exception
     */
    public function validate($value) : void;

    /**
     * Assert positive (true) condition, for example in an integer validator, assert that the value is indeed of
     * integer type.
     *
     * @param $value
     * @throws \Exception
     */
    public function assertTrue($value) : void;

    /**
     * Assert negative (false) condition, for example in an integer validator, assert that the value is NOT of
     * integer type.
     *
     * @param $value
     * @throws \Exception
     */
    public function assertFalse($value) : void;

    /**
     * @param ValidatorConfigInterface $config
     * @return ValidatorInterface
     */
    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface;

    /**
     * @return ValidatorConfigInterface
     */
    public function getConfig();
}