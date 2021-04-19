<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Dumper;

use LDL\Framework\Helper\IterableHelper;
use LDL\Validators\Chain\ValidatorChainInterface;

class ValidatorChainExprDumper implements ValidatorChainDumperInterface
{
    public static function dump(ValidatorChainInterface $collection) : string
    {
        if($collection->count() === 0){
            return '';
        }

        $string = IterableHelper::map($collection, static function($validator){
            if($validator instanceof ValidatorChainInterface){
                return self::dump($validator);
            }

            return $validator->getConfig()->isNegated() ? sprintf('!%s', get_class($validator)) : get_class($validator);
        });

        $string = implode($collection->getConfig()->getOperator(), $string);

        $string = $collection->count() === 1 ? $string : sprintf('(%s)', $string);


        if($collection->getConfig()->isNegated()){
            $string = sprintf('!%s', $string);
        }

        return $string;

    }
}
