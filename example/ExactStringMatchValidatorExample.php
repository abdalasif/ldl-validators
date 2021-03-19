<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Validators\ExactStringMatchValidator;
use LDL\Validators\Config\ExactStringMatchValidatorConfig;

echo "Create ExactStringMatch validator from config\n";
echo "Set config value: 'testFile.txt'\n";

$config = ExactStringMatchValidatorConfig::fromArray([
    'value' => 'testFile.txt'
]);

$validator = ExactStringMatchValidator::fromConfig($config);

echo "Read files in dir: 'files'\n";

$path    = __DIR__.'/files';
$files = array_diff(scandir($path), array('.', '..'));

foreach($files as $file){
    echo "Validate filename: $file\n";

    try{
        $validator->validate($file);
    }catch(\Exception $e){
        echo "EXCEPTION: {$e->getMessage()}\n";
    }
}