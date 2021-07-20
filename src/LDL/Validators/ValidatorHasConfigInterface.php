<?php declare(strict_types=1);

namespace LDL\Validators;

interface ValidatorHasConfigInterface extends \JsonSerializable
{
    /**
     * @param array $data
     * @return ValidatorInterface
     */
    public static function fromConfig(array $data=[]);

    /**
     * @return array
     */
    public function getConfig();

    /**
     * @return array
     */
    public function jsonSerialize() : array;
}