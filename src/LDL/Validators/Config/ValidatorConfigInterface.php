<?php declare(strict_types=1);

namespace LDL\Validators\Config;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Framework\Base\Contracts\ToArrayInterface;

interface ValidatorConfigInterface extends ArrayFactoryInterface, ToArrayInterface, \JsonSerializable
{
    /**
     * @return bool
     */
    public function isNegated() : bool;

    /**
     * @return bool
     */
    public function isDumpable() : bool;

    /**
     * @return string|null
     */
    public function getDescription() : ?string;

    /**
     * @return bool
     */
    public function hasDescription() : bool;

}