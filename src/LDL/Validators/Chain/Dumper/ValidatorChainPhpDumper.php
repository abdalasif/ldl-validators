<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Dumper;

use LDL\Validators\Chain\ValidatorChainInterface;

class ValidatorChainPhpDumper implements ValidatorChainDumperInterface
{
    public static function dump(ValidatorChainInterface $collection) : array
    {
        if(count($collection) === 0){
            return [];
        }

        $data = [
            'parent' => get_class($collection)
        ];

        foreach($collection->filterDumpableItems() as $validator){
            $temp = [
                'class' => get_class($validator),
            ];

            if($validator instanceof ValidatorChainInterface){
                $data['children'][] = self::dump($validator);
                continue;
            }

            $temp['config'] = $validator->getConfig()->toArray();
            $data['children'][] = $temp;
        }

        return $data;
    }
}
