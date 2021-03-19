<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Dumper;

use LDL\Validators\Chain\ValidatorChainInterface;
use LDL\Validators\HasValidatorConfigInterface;

class ValidatorChainDumper implements ValidatorChainDumperInterface
{
    public static function dump(ValidatorChainInterface $collection, string $file) : void
    {
        $encode = [];

        foreach($collection->filterDumpableItems() as $key => $validator){
            $dump = [
                'key' => $key,
                'validator' => get_class($validator)
            ];

            if($validator instanceof HasValidatorConfigInterface){
                $dump['config'] = [
                    'class' => get_class($validator->getConfig()),
                    'config' => $validator->getConfig()->toArray()
                ];
            }

            $encode[] = $dump;
        }

        $fp = fopen($file, 'wb');
        fwrite($fp, json_encode($encode, \JSON_THROW_ON_ERROR));
        fclose($fp);
    }
}
