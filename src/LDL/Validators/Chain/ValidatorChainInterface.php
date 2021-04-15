<?php declare(strict_types=1);

namespace LDL\Validators\Chain;

use LDL\Framework\Base\Collection\Contracts\AppendableInterface;
use LDL\Framework\Base\Collection\Contracts\BeforeAppendInterface;
use LDL\Framework\Base\Collection\Contracts\BeforeRemoveInterface;
use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Framework\Base\Collection\Contracts\FilterByClassInterface;
use LDL\Framework\Base\Collection\Contracts\FilterByInterface;
use LDL\Framework\Base\Collection\Contracts\LockAppendInterface;
use LDL\Framework\Base\Collection\Contracts\RemovableInterface;
use LDL\Framework\Base\Collection\Contracts\TruncateInterface;
use LDL\Framework\Base\Contracts\LockableObjectInterface;
use LDL\Validators\ValidatorInterface;

interface ValidatorChainInterface extends CollectionInterface, LockableObjectInterface, ValidatorInterface, BeforeAppendInterface, AppendableInterface, LockAppendInterface, BeforeRemoveInterface, RemovableInterface, TruncateInterface, FilterByInterface, FilterByClassInterface
{
    public function append($item, $key = null, bool $dumpable = true): CollectionInterface;

    public function filterDumpableItems() : ValidatorChainInterface;

    public function getSuccededValidators() : ValidatorChainInterface;

    public function getErrorValidators() : ValidatorChainInterface;

    public function getLastExecuted(): ?ValidatorInterface;
}