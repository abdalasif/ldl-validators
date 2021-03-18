<?php declare(strict_types=1);

namespace LDL\Type\Collection\Validator\Exception;

use LDL\Validators\Chain\Exception\ValidatorChainException;
use Throwable;

class ValidatorChainSoftValidationException extends ValidatorChainException
{
    /**
     * @var iterable
     */
    private $exceptions;

    public function __construct(
        string $message = "",
        int $code = 0,
        Throwable $previous = null,
        iterable $exceptions
    )
    {
        parent::__construct($message, $code, $previous);
    }

    public function getExceptions() : iterable
    {
        return $this->exceptions;
    }
}