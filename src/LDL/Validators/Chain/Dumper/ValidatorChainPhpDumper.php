<?php declare(strict_types=1);

namespace LDL\Validators\Chain\Dumper;

use LDL\Validators\ValidatorHasConfigInterface;
use LDL\Validators\Chain\ValidatorChainInterface;
use LDL\Validators\Chain\Item\ValidatorChainItemInterface;

class ValidatorChainPhpDumper implements ValidatorChainDumperInterface
{
    public static function dump(ValidatorChainInterface $chain): array
    {
        $chainItems = $chain->getChainItems();

        if (count($chainItems) === 0) {
            return [];
        }

        $data = [
            'parent' => get_class($chain)
        ];

        if (!$chainItems instanceof FilterDumpableInterface) {
            return $data;
        }

        /**
         * @var ValidatorChainItemInterface $chainItem
         */
        foreach ($chainItems->filterDumpableItems() as $chainItem) {
            $validator = $chainItem->getValidator();
            $temp = [
                'class' => get_class($validator),
            ];

            if ($validator instanceof ValidatorChainInterface) {
                $data['children'][] = self::dump($validator);
                continue;
            }

            $config = $validator instanceof ValidatorHasConfigInterface ? $validator->getConfig() : [];

            $temp['config'] = $config;
            $data['children'][] = $temp;
        }

        return $data;
    }
}
