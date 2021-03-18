<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Validators\ExactFileNameValidator;
use LDL\Validators\Config\ExactFileNameValidatorConfig;

echo "Create ExactFileName validator from config\n";
echo "Set config name: 'testFile.txt'\n";

$config = ExactFileNameValidatorConfig::fromArray([
    'name' => 'testFile.txt'
]);

$validator = ExactFileNameValidator::fromConfig($config);

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