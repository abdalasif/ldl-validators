<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Dumper;

use LDL\Framework\Helper\IterableHelper;
use LDL\Validators\Chain\Item\ValidatorChainItemInterface;
use LDL\Validators\Chain\ValidatorChainHasOperatorInterface;
use LDL\Validators\Chain\ValidatorChainInterface;
use LDL\Validators\NegatedValidatorInterface;

class ValidatorChainExprDumper implements ValidatorChainDumperInterface
{
    public static function dump(ValidatorChainInterface $chain) : string
    {
        if($chain->getChainItems()->count() === 0){
            return '';
        }

        $chainItems = IterableHelper::filter($chain->getChainItems(), static function($v){
            if(!$v->isDumpable()){
                return false;
            }

            return true;
        });

        $string = IterableHelper::map(
            $chainItems,
            /**
             * @var ValidatorChainItemInterface $chainItem
             * @return string
             */
            static function($chainItem){
                if(!$chainItem->isDumpable()){
                    return false;
                }

                $validator = $chainItem->getValidator();

                if($validator instanceof ValidatorChainInterface){
                    return self::dump($validator);
                }

                $class = get_class($validator);

                if($validator instanceof NegatedValidatorInterface){
                    return $validator->isNegated() ? sprintf('!%s', $class) : $class;
                }

                return $class;
            }
        );

        $string = $chain instanceof ValidatorChainHasOperatorInterface ? implode($chain->getOperator(), $string) : implode(' , ', $string);

        $string = $chain->getChainItems()->count() === 1 ? $string : sprintf('(%s)', $string);

        if($chain instanceof NegatedValidatorInterface && $chain->isNegated()){
            $string = sprintf('!%s', $string);
        }

        return $string;
    }
}
