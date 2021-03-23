<?php declare(strict_types=1);

namespace LDL\Validators\Chain;

use LDL\Framework\Base\Collection\Contracts\AppendableInterface;
use LDL\Framework\Base\Collection\Contracts\BeforeAppendInterface;
use LDL\Framework\Base\Collection\Contracts\BeforeRemoveInterface;
use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Framework\Base\Collection\Contracts\LockAppendInterface;
use LDL\Framework\Base\Collection\Contracts\RemovableInterface;
use LDL\Validators\ValidatorInterface;

interface ValidatorChainInterface extends CollectionInterface, ValidatorInterface, AppendableInterface, LockAppendInterface, BeforeAppendInterface, RemovableInterface, BeforeRemoveInterface
{
    public function append($item, $key = null, bool $dumpable = true): CollectionInterface;

    public function filterDumpableItems() : ValidatorChainInterface;
}