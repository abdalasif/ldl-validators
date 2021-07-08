<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Dumper;

use LDL\Framework\Helper\IterableHelper;
use LDL\Validators\Chain\ValidatorChainInterface;
use LDL\Validators\Config\NegatedValidatorConfigInterface;
use LDL\Validators\ValidatorHasConfigInterface;
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

                $msg = sprintf(
                    '"%s"',
                    $validator->getDescription()
                );

                if(!$validator instanceof ValidatorHasConfigInterface){
                    return $msg;
                }

                $config = $validator->getConfig();

                if($config instanceof NegatedValidatorConfigInterface){
                    return sprintf(
                        '%s"%s"',
                        $config->isNegated() ? ' NOT ' : '',
                        $validator->getDescription()
                    );
                }

                return $msg;
        });

        $string = implode($collection->getConfig()->getOperator(), $string);

        $string = $collection->count() === 1 ? $string : sprintf('%s', $string);

        if($collection->getConfig()->isNegated()){
            $string = sprintf('NOT: "%s"', $string);
        }

        return $string;
    }
}
