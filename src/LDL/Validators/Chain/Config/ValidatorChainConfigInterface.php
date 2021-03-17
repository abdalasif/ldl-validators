<?php declare(strict_types=1);

namespace LDL\Validators\Collection\Validator\Chain\Config;

use LDL\Framework\Base\Contracts\LockableObjectInterface;
use LDL\Validators\Collection\Interfaces\CollectionInterface;

interface ValidatorChainConfigInterface extends CollectionInterface, LockableObjectInterface, \JsonSerializable
{

}