<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Dumper;

use LDL\Framework\Helper\IterableHelper;
use LDL\Validators\Chain\ValidatorChainInterface;
use LDL\Validators\ValidatorInterface;

class ValidatorChainHumanDumper implements ValidatorChainDumperInterface
{
    public static function dump(ValidatorChainInterface $collection) : string
    {
        if($collection->count() === 0){
            return '';
        }

        $string = IterableHelper::map(
            $collection,
            /**
             * @var ValidatorInterface $validator
             * @return string
             */
            static function($validator) : string
            {
                if($validator instanceof ValidatorChainInterface){
                    return self::dump($validator);
                }

                if(!$validator->getConfig()->hasDescription()){
                    return '<NO DESCRIPTION WAS SET>';
                }

                return sprintf(
                    '%s"%s"',
                    $validator->getConfig()->isNegated() ? ' NOT ' : '',
                    $validator->getConfig()->getDescription()
                );
        });

        $string = implode($collection->getConfig()->getOperator(), $string);

        $string = $collection->count() === 1 ? $string : sprintf('%s', $string);

        if($collection->getConfig()->isNegated()){
            $string = sprintf('NOT: "%s"', $string);
        }

        return $string;
    }
}
