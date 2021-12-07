<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Loader;

use Exception;
use LDL\File\File;
use LDL\Framework\Base\Contracts\JsonFactoryInterface;
use LDL\Framework\Base\Exception\JsonFactoryException;
use LDL\Validators\Chain\Loader\ValidatorChainPhpLoader;
use LDL\Framework\Base\Contracts\JsonFileFactoryInterface;
use LDL\Framework\Base\Exception\JsonFileFactoryException;

class ValidatorChainJsonLoader implements JsonFactoryInterface, JsonFileFactoryInterface
{
    /**
     * @param string $file
     * @throws JsonFileFactoryException
     * @return mixed
     */
    public static function fromJsonFile(string $file)
    {
        try {
            return ValidatorChainPhpLoader::fromObject(
                self::_jsonDecode(
                    (new File($file))->getLinesAsString()
                )
            );
        } catch (Exception $e) {
            throw new JsonFileFactoryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param string $json
     * @throws JsonFactoryException
     * @return mixed
     */
    public static function fromJsonString(string $json)
    {
        try {
            return ValidatorChainPhpLoader::fromObject(self::_jsonDecode($json));
        } catch (Exception $e) {
            throw new JsonFactoryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * decode json
     *
     * @param  string $json
     * @return object
     */
    private static function _jsonDecode(string $json)
    {
        return json_decode(
            $json,
            false,
            512,
            $options ?? \JSON_THROW_ON_ERROR
        );
    }
}
