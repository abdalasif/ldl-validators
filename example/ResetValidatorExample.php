<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Validators\Chain\OrValidatorChain;
use LDL\Validators\Config\BasicValidatorConfig;
use LDL\Validators\Config\ValidatorConfigInterface;
use LDL\Validators\RegexValidator;
use LDL\Validators\ValidatorInterface;
use LDL\Validators\ResetValidatorInterface;
use LDL\Validators\Traits\ValidatorValidateTrait;
use LDL\Validators\HasValidatorResultInterface;

class ResetValidatorExample implements ValidatorInterface, HasValidatorResultInterface, ResetValidatorInterface
{
    use ValidatorValidateTrait;

    /**
     * @var BasicValidatorConfig
     */
    private $config;

    /**
     * @var int
     */
    private $internalState;

    public function __construct(bool $negated=false, bool $dumpable=true, string $description=null)
    {
        $this->config = new BasicValidatorConfig($negated, $dumpable, $description);
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

    public static function fromConfig(ValidatorConfigInterface $config): ValidatorInterface
    {
        return new self($config->isNegated());
    }

    public function getConfig(): BasicValidatorConfig
    {
        return $this->config;
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
$chain->append(new ResetValidatorExample());

echo "Validate: 123\n";
$chain->validate(123);
echo "OK!\n";

echo "Checking internal state\n";
$firstValue = $chain->getLast()->getResult();
echo $firstValue."\n";

echo "Validate: 456\n";
$chain->validate(456);
echo "OK!\n";

echo "Checking internal state\n";
$secondValue = $chain->getLast()->getResult();
echo $secondValue."\n";

if($firstValue === $secondValue){
    echo "EXCEPTION: validator was not reset\n";
}