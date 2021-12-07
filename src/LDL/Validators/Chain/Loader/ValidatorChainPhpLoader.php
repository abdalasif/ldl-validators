<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Loader;

use LDL\Framework\Base\Contracts\ObjectFactoryInterface;
use LDL\Framework\Base\Exception\ObjectFactoryException;

class ValidatorChainPhpLoader implements ObjectFactoryInterface
{
    /**
     * @param object $obj
     * @throws ObjectFactoryException
     * @return mixed
     */
    public static function fromObject(object $obj)
    {
        try {
            return self::_iterateChain($obj->children, $obj->parent);
        } catch (\Exception $e) {
            throw new ObjectFactoryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * recreates the chain from array at n levels
     *
     * @param  array $children
     * @param  string $parentValidatorChain
     * 
     * @return mixed ValidatorChainInterface | ValidatorInterface
     */
    private static function _iterateChain(array $children, string $parentValidatorChain)
    {
        $childValidatorsAndChains = [];

        foreach ($children as $validatorChainItem) {
            if (isset($validatorChainItem->children)) {
                $childValidatorsAndChains[] = self::_iterateChain(
                    $validatorChainItem->children,
                    $validatorChainItem->parent
                );

                continue;
            }

            $validator = sprintf('\%s', $validatorChainItem->class);

            $config = (array) $validatorChainItem->config;

            $childValidatorsAndChains[] = count($config) ?
                $validator::fromConfig($config) :
                new $validator;
        }

        $parentValidatorChain = sprintf('\%s', $parentValidatorChain);

        return new $parentValidatorChain($childValidatorsAndChains);
    }
}