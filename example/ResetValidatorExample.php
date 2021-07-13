<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Validators\Chain\Item\ValidatorChainItem;
use LDL\Validators\Chain\OrValidatorChain;
use LDL\Validators\Config\Traits\ValidatorConfigTrait;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\RegexValidator;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\ValidatorInterface;
use LDL\Validators\ResetValidatorInterface;
use LDL\Validators\Traits\ValidatorValidateTrait;
use LDL\Validators\HasValidatorResultInterface;
use LDL\Validators\Traits\ValidatorHasConfigInterfaceTrait;
use LDL\Validators\Traits\ValidatorDescriptionTrait;

class ResetValidatorExampleConfig implements ValidatorConfigInterface
{
    use ValidatorConfigTrait;

    public static function fromArray(array $data = []): ArrayFactoryInterface
    {
        return new self();
    }

    public function toArray(): array
    {
        return [];
    }

}

class ResetValidatorExample implements ValidatorInterface, HasValidatorResultInterface, ResetValidatorInterface
{
    use ValidatorValidateTrait;
    use NegatedValidatorTrait;
    use ValidatorHasConfigInterfaceTrait;
    use ValidatorDescriptionTrait;

    private const DESCRIPTION = 'Reset validator';

    /**
     * @var int
     */
    private $internalState;

    public function __construct(bool $negated=false, string $description=null)
    {
        $this->_tNegated = $negated;
        $this->_tDescription = $description ?? self::DESCRIPTION;
        $this->_tConfig = new ResetValidatorExampleConfig();
    }

    public function reset()
    {
        $this->internalState = uniqid('', true);
    }

    public function assertTrue($value): void
    {
        if(!is_int($value)){
            throw new \Exception("Invalid value, must be an integer");
        }

        $this->internalState = uniqid('', true);
    }

    public function getResult()
    {
        return $this->internalState;
    }

    public function assertFalse($value): void
    {
    }

    public static function fromConfig(ValidatorConfigInterface $config, bool $negated = false, string $description=null): ValidatorInterface
    {
        return new self($negated, $description);
    }
}

echo "Create OrValidatorChain\n";
echo "Append RegexValidator with regex: #[a-z]+#'\n";

$chain = new OrValidatorChain([
    new RegexValidator('#[a-z]+#')
]);

echo "Validate: 'hello'\n";
$chain->validate('hello');

echo "Validate: 'world'\n";
$chain->validate('world');

echo "Append ResetValidatorExample\n";
$chain->append(new ValidatorChainItem(new ResetValidatorExample()));

echo "Validate: 123\n";
$chain->validate(123);
echo "OK!\n";

echo "Checking internal state\n";
$firstValue = $chain->getLast()->getValidator()->getResult();
echo $firstValue."\n";

echo "Validate: 456\n";
$chain->validate(456);
echo "OK!\n";

echo "Checking internal state\n";
$secondValue = $chain->getLast()->getValidator()->getResult();
echo $secondValue."\n";

if($firstValue === $secondValue){
    echo "EXCEPTION: validator was not reset\n";
}