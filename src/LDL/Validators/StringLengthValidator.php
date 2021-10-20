<?php declare(strict_types=1);

namespace LDL\Validators;

use LDL\Framework\Base\Contracts\Type\ToStringInterface;
use LDL\Framework\Helper\ComparisonOperatorHelper;
use LDL\Framework\Helper\MbEncodingHelper;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\Traits\ValidatorDescriptionTrait;
use LDL\Validators\Traits\ValidatorValidateTrait;

class StringLengthValidator implements ValidatorInterface, NegatedValidatorInterface, ValidatorHasConfigInterface
{
    use ValidatorValidateTrait;
    use NegatedValidatorTrait;
    use ValidatorDescriptionTrait;

    private const DESCRIPTION='Validates string length';

    /**
     * @var string
     */
    private $operator;

    /**
     * @var int
     */
    private $length;

    /**
     * @var string
     * @see https://www.php.net/manual/en/mbstring.supported-encodings.php For a list of available encodings
     */
    private $encoding;

    /**
     * StringLengthValidator constructor.
     * @param int $length
     * @param string $operator
     * @param bool $negated
     * @param string|null $description
     * @param string $encoding
     */
    public function __construct(
        int $length,
        string $operator,
        string $encoding = null,
        bool $negated = false,
        string $description=null
    )
    {
        ComparisonOperatorHelper::isValid($operator);

        if(null !== $encoding){
            MbEncodingHelper::validate($encoding);
        }

        if($length < 0){
            $msg = "Length must be a number greater or equal to 0, \"$length\" was given";
            throw new \InvalidArgumentException($msg);
        }

        $this->length = $length;
        $this->operator = $operator;
        $this->_tNegated = $negated;
        $this->_tDescription = $description ?? self::DESCRIPTION;
        $this->encoding = $encoding;
    }

    public function assertTrue($value): void
    {
        if($value instanceof ToStringInterface){
            $value = $value->toString();
        }

        $value = (string)$value;

        $result = ComparisonOperatorHelper::compare(
            mb_strlen($value, $this->encoding),
            $this->length,
            $this->operator
        );

        if($result){
            return;
        }

        $msg = sprintf(
            'Failed to validate length of string "%s" is %s %s',
            $value,
            $this->operator,
            $this->length
        );

        throw new \LogicException($msg);
    }

    public function assertFalse($value): void
    {
        if($value instanceof ToStringInterface){
            $value = $value->toString();
        }

        $value = (string)$value;

        $result = ComparisonOperatorHelper::compareInverse(
            mb_strlen($value, $this->encoding),
            $this->length,
            $this->operator
        );

        if($result){
            return;
        }

        $msg = sprintf(
            'Failed to validate length of string "%s" is %s %s',
            $value,
            ComparisonOperatorHelper::getOppositeOperator($this->operator),
            $this->length
        );

        throw new \LogicException($msg);
    }
    public function jsonSerialize(): array
    {
        return $this->getConfig();
    }

    /**
     * @return int
     */
    public function getLength() : int
    {
        $this->length;
    }

    /**
     * @return string
     */
    public function getOperator() : string
    {
        return $this->operator;
    }

    /**
     * @param array $data
     * @return ValidatorInterface
     * @throws \InvalidArgumentException
     */
    public static function fromConfig(array $data = []): ValidatorInterface
    {
        $required = [
            'length',
            'operator'
        ];

        foreach($required as $r) {
            if (!array_key_exists($r, $data)) {
                $msg = sprintf("Missing property '%s' in %s", $r, __CLASS__);
                throw new \InvalidArgumentException($msg);
            }
        }

        return new self(
            $data['length'],
            $data['operator'],
            $data['encoding'] ?? null,
            array_key_exists('negated', $data) ? (bool)$data['negated'] : false,
            $data['description'] ?? null
        );
    }

    /**
     * @return string
     */
    public function getEncoding() : string
    {
        return $this->encoding;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'length' => $this->length,
            'operator' => $this->operator,
            'negated' => $this->_tNegated,
            'description' => $this->getDescription(),
            'encoding' => $this->getEncoding()
        ];
    }
}
