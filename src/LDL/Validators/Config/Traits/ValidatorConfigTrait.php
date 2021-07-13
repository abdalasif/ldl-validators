<?php declare(strict_types=1);

namespace LDL\Validators\Config\Traits;

trait ValidatorConfigTrait
{
    public function jsonSerialize() : array
    {
        return $this->toArray();
    }
}