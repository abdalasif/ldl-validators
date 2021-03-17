<?php declare(strict_types=1);

namespace LDL\Validators\Config;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Framework\Base\Contracts\ToArrayInterface;

interface ValidatorConfigInterface extends ArrayFactoryInterface, ToArrayInterface, \JsonSerializable
{
    public function isStrict() : bool;
}