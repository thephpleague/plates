<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use League\Plates\Engine;

$plates = new Engine();
echo $plates->render(__DIR__ . '/templates/main.phtml', [
    'name' => 'RJ & Emily',
    'sidebar_style' => 'simple',
]);
