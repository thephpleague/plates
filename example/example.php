<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Create new Plates instance
$templates = League\Plates\Engine::create(__DIR__ . '/templates');

// Preassign data to the layout
$templates->addData(['company' => 'The Company Name'], 'layout');

// Render a template
echo $templates->render('profile', ['name' => 'Jonathan']);
