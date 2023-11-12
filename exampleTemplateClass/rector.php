<?php

include __DIR__.'/../vendor/autoload.php';

use League\Plates\RectorizeTemplate;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__.'/Templates/Layout.php',
    ]);
    $rectorConfig->rule(RectorizeTemplate::class);
};

// vendor/bin/rector process exampleTemplateClass/Templates --config ./exampleTemplateClass/rector.php
