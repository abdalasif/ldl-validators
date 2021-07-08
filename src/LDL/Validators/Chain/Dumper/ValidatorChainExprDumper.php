<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Dumper;

use LDL\Framework\Helper\IterableHelper;
use LDL\Validators\Chain\ValidatorChainInterface;
use LDL\Validators\Config\NegatedValidatorConfigInterface;
use LDL\Validators\ValidatorHasConfigInterface;
use LDL\Validators\ValidatorInterface;

class ValidatorChainExprDumper implements ValidatorChainDumperInterface
{
    public static function dump(ValidatorChainInterface $chain) : string
    {
        if($chain->count() === 0){
            return '';
        }

        $string = IterableHelper::map(
            $chain,
            /**
             * @var ValidatorInterface $validator
             * @return string
             */
            static function($validator){
                if($validator instanceof ValidatorChainInterface){
                    return self::dump($validator);
                }

                $class = get_class($validator);

                if(!$validator instanceof ValidatorHasConfigInterface){
                    return $class;
                }

                $config = $validator->getConfig();

                if($config instanceof NegatedValidatorConfigInterface){
                    return $config->isNegated() ? sprintf('!%s', $class) : $class;
                }

                return $class;
            }
        );

        $string = implode($chain->getConfig()->getOperator(), $string);

        $string = $chain->count() === 1 ? $string : sprintf('(%s)', $string);

        if($chain->getConfig()->isNegated()){
            $string = sprintf('!%s', $string);
        }

        return $string;
    }
}
