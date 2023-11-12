<?php


use League\Plates\RectorizeTemplate;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(RectorizeTemplate::class);
};

// vendor/bin/rector process exampleTemplateClass/Templates --config ./exampleTemplateClass/rector.php --debug
// vendor/bin/rector process exampleTemplateClass/Templates --config ./vendor/league/plates/exampleTemplateClass/rector.php
