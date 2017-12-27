<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use League\Plates\Engine;

$plates = new Engine(['base_dir' => __DIR__ . '/templates']);
$plates->addFolder('components', ['partials/components', '']);
try {
    echo $plates->render('main', [
        'name' => 'RJ & Emily',
        'sidebar_style' => 'simple',
    ]);
} catch (Throwable $e) {
    echo $e;
}

