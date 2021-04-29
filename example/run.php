#!/usr/bin/env php
<?php declare(strict_types=1);

function runTest($file)
{
    echo "\n\nRunning example: $file\n";
    echo str_repeat('#', 80)."\n\n\n";

    try{
        require $file;

        return true;
    }catch(\Exception $e){

        echo "TEST FAIL!!!!!\n";
        echo "$file\n";
        echo $e->getMessage()."\n";

        return false;
    }
}

$dp = new DirectoryIterator(__DIR__);

$sleep = isset($_SERVER['argv'][1]) ? (int)$_SERVER['argv'][1] : 3;

foreach($dp as $file){

    if($file->isDir() || $file->getRealPath() === __FILE__ || $file->getExtension() !==  'php'){
        continue;
    }

    if(false === runTest($file->getRealPath())){
        exit(1);
    }

    sleep($sleep);
}

echo "\n\n****************************************************\n";
echo "All examples have run with no exceptions\n";
exit(0);
