<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Validators\Chain\Item\ValidatorChainItem;
use LDL\Validators\Chain\OrValidatorChain;
use LDL\Validators\RegexValidator;
use LDL\Validators\Traits\NegatedValidatorTrait;
use LDL\Validators\ValidatorInterface;
use LDL\Validators\Traits\ValidatorValidateTrait;
use LDL\Validators\HasValidatorResultInterface;
use LDL\Validators\Traits\ValidatorDescriptionTrait;
use LDL\Validators\BeforeValidateInterface;
use LDL\Validators\Traits\ValidatorBeforeValidateTrait;

class BeforeValidateValidatorExample implements ValidatorInterface, HasValidatorResultInterface, BeforeValidateInterface
{
    use ValidatorValidateTrait;
    use NegatedValidatorTrait;
    use ValidatorDescriptionTrait;
    use ValidatorBeforeValidateTrait;

    private const DESCRIPTION = 'Before validate validator';

    /**
     * @var int
     */
    private $internalState;

    public function __construct(bool $negated=false, string $description=null)
    {
        $this->_tNegated = $negated;
        $this->_tDescription = $description ?? self::DESCRIPTION;

        $this->onBeforeValidate()->append(function(){
            $this->internalState = uniqid('', true);
        });
    }

    public function assertTrue($value): void
    {
        if(!is_int($value)){
            throw new \Exception("Invalid value, must be an integer");
        }
    }

    public function getResult()
    {
        return $this->internalState;
    }

    public static function fromConfig(array $data = []): ValidatorInterface
    {
        return new self($data['negated'], $data['description']);
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

echo "Append BeforeValidateExample\n";
$chain->getChainItems()->append(new ValidatorChainItem(new BeforeValidateValidatorExample()));

echo "Validate: 123\n";
$chain->validate(123);
$chain->getChainItems()->lock();
echo "OK!\n";

echo "Checking internal state\n";
$firstValue = $chain->getChainItems()
    ->getLast()
    ->getValidator()
    ->getResult();
echo $firstValue."\n";

echo "Validate: 456\n";
$chain->validate(456);
echo "OK!\n";

echo "Checking internal state\n";
$secondValue = $chain->getChainItems()
    ->getLast()
    ->getValidator()
    ->getResult();
echo $secondValue."\n";

if($firstValue === $secondValue){
    var_dump("$firstValue === $secondValue");
    echo "EXCEPTION: validator was not reset!!!\n";
}