<?php  echo "This file is peridot.php";?>


<?php

use Evenement\EventEmitterInterface;
use Peridot\Reporter\CodeCoverage\CodeCoverageReporter;
use Peridot\Reporter\CodeCoverageReporters;

return function (EventEmitterInterface $emitter) {
    $coverage = new CodeCoverageReporters($emitter);
    $coverage->register();

    $emitter->on('code-coverage.start', function (CodeCoverageReporter $reporter) {
        $reporter->addDirectoryToWhitelist(__DIR__ . '/src');
    });
};
