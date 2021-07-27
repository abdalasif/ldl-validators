<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Framework\Base\Collection\CallableCollectionInterface;

interface BeforeValidateInterface
{
    public function onBeforeValidate() : CallableCollectionInterface;
}