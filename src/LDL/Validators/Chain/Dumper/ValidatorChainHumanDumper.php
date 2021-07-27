<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Dumper;

use LDL\Framework\Helper\IterableHelper;
use LDL\Validators\Chain\Item\ValidatorChainItemInterface;
use LDL\Validators\Chain\ValidatorChainHasOperatorInterface;
use LDL\Validators\Chain\ValidatorChainInterface;
use LDL\Validators\NegatedValidatorInterface;

class ValidatorChainHumanDumper implements ValidatorChainDumperInterface
{
    private const NO_ITEMS_FOUND = '<NO DUMPABLE ITEMS FOUND>';

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

        if(0 === count($chainItems)){
            return sprintf('%s', self::NO_ITEMS_FOUND);
        }

        $string = IterableHelper::map(
            $chainItems,
            /**
             * @var ValidatorChainItemInterface $chainItem
             * @return string
             */
            static function($chainItem) : string
            {
                $validator = $chainItem->getValidator();

                if($validator instanceof ValidatorChainInterface){
                    return self::dump($validator);
                }

                $msg = sprintf(
                    '"%s"',
                    $validator->getDescription()
                );

                if($validator instanceof NegatedValidatorInterface){
                    return sprintf(
                        '%s"%s"',
                        $validator->isNegated() ? ' NOT ' : '',
                        $validator->getDescription()
                    );
                }

                return $msg;
        });

        $string = $chain instanceof ValidatorChainHasOperatorInterface ? implode($chain->getOperator(), $string) : implode(' , ', $string);

        $string = $chain->getChainItems()->count() === 1 ? $string : sprintf('%s', $string);

        if($chain instanceof NegatedValidatorInterface && $chain->isNegated()){
            $string = sprintf('NOT: "%s"', $string);
        }

        return $string;
    }
}
