<?php

include '../vendor/autoload.php';

use Templates\Layout;
use Templates\Profile;

// Create new Plates instance
$templates = new League\Plates\Engine(__DIR__.'/../example/templates');

// Preassign data to the layout
$templates->addData(['company' => 'The Company Name'], Layout::class);

// Render a template
echo $templates->render(new Profile('Jonathan'));
