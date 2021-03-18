<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Validators\FileExtensionValidator;
use LDL\Validators\Config\FileExtensionValidatorConfig;

echo "Create FileExtension validator from config\n";
echo "Set config extension: 'php'\n";

$config = FileExtensionValidatorConfig::fromArray([
    'extension' => 'php'
]);

$validator = FileExtensionValidator::fromConfig($config);

echo "Read files in dir: 'files'\n";

$path    = __DIR__.'/files';
$files = array_diff(scandir($path), array('.', '..'));

foreach($files as $file){
    echo "Validate filename: $file\n";

    $extension = pathinfo($file, \PATHINFO_EXTENSION);

    try{
        $validator->validate($extension);
    }catch(\Exception $e){
        echo "EXCEPTION: {$e->getMessage()}\n";
    }
}