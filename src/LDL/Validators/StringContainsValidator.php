<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\Traits\ValidatorDescriptionTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;
use LDL\Framework\Helper\MbEncodingHelper;

class StringContainsValidator implements ValidatorInterface, NegatedValidatorInterface, ValidatorHasConfigInterface
{
    use ValidatorValidateTrait;
    use NegatedValidatorTrait;
    use ValidatorDescriptionTrait;

    /**
     * @var string
     */
    private $contains;

    /**
     * @var string
     */
    private $encoding;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var bool
     */
    private $toLower;

    public function __construct(
        string $contains,
        string $encoding=null,
        int $offset = 0,
        bool $toLower = true,
        bool $negated=false,
        string $description=null
    )
    {
        if(null !== $encoding){
            MbEncodingHelper::validate($encoding);
        }

        $this->offset = $offset;
        $this->encoding = $encoding;
        $this->contains = $toLower ? $contains : mb_strtolower($contains, $encoding);
        $this->toLower = $toLower;
        $this->_tNegated = $negated;

        if(null !== $description){
            $this->_tDescription = $description;
            return;
        }

        $this->_tDescription = sprintf(
            'String must%scontain: %s',
            $negated ? ' NOT ' : ' ',
            $this->contains
        );
    }

    /**
     * @return string
     */
    public function getContains(): string
    {
        return $this->contains;
    }

    /**
     * @return string|null
     */
    public function getEncoding() : ?string
    {
        return $this->encoding;
    }

    /**
     * @return int
     */
    public function getOffset() : int
    {
        return $this->offset;
    }

    /**
     * @return bool
     */
    public function isToLower(): bool
    {
        return $this->toLower;
    }

    public function assertTrue($value): void
    {
        $value = (string)$value;
        $value = $this->toLower ? mb_strtolower($value, $this->encoding) : $value;

        if(mb_strpos($value, $this->contains, $this->offset, $this->encoding) !== false){
            return;
        }

        throw new \LogicException("String: \"$value\" does not contains criteria: {$this->contains}");
    }

    public function assertFalse($value): void
    {
        $value = (string)$value;
        $value = $this->toLower ? mb_strtolower($value, $this->encoding) : $value;

        if(mb_strpos($value, $this->contains, $this->offset, $this->encoding) === false){
            return;
        }

        $msg = sprintf(
            'Failed to validate that string "%s" does NOT contain "%s"',
            $value,
            $this->contains
        );
        throw new \LogicException($msg);
    }

    public function jsonSerialize(): array
    {
        return $this->getConfig();
    }

    /**
     * @param array $data
     * @return ValidatorInterface
     * @throws \InvalidArgumentException
     */
    public static function fromConfig(array $data = []): ValidatorInterface
    {
        if(!array_key_exists('contains', $data)){
            $msg = sprintf(
                'Missing property \'contains\' when calling %s::%s',
                __CLASS__,
                __METHOD__
            );
            throw new \InvalidArgumentException($msg);
        }

        return new self(
            $data['contains'],
            $data['encoding'] ?? null,
            array_key_exists('offset', $data) ? (int)$data['offset'] : true,
            array_key_exists('toLower', $data) ? (bool)$data['toLower'] : true,
            array_key_exists('negated', $data) ? (bool)$data['negated'] : false,
            $data['description'] ?? null
        );
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'contains' => $this->contains,
            'encoding' => $this->encoding,
            'offset' => $this->offset,
            'toLower' => $this->toLower,
            'negated' => $this->_tNegated,
            'description' => $this->getDescription()
        ];
    }
}
