<?php declare(strict_types=1);

namespace LDL\Validators\Chain;

use LDL\Framework\Base\Collection\Contracts\AppendableInterface;
use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Validators\ValidatorInterface;

interface ValidatorChainInterface extends CollectionInterface, AppendableInterface, ValidatorInterface
{

}