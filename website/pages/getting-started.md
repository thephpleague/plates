Getting started
===============

## The engine

Plates uses a central object called the `Engine`, which is used to store the environment configuration and extensions. It helps decouple your templates from the file system and other dependencies. For example, if you want to change the folder where your templates are stored, you can do so by simply changing the path in one location.

Templates themselves are very simple objects. The only dependency they require is an instance of the engine object. This design makes it easy to use dependency injection to inject your templates into other objects, which in return makes them easier to test and mock.

## Installation

Plates is available via Composer:

~~~.language-javascript
{
    "require": {
        "reinink/plates": "1.*"
    }
}
~~~

## Setup

~~~.language-php
<?php

// Include Composer autoloader
require 'vendor/autoload.php';

// Create new Plates engine
$engine = new \Plates\Engine('/path/to/templates');

// Add any any additional folders
$engine->addFolder('emails', '/path/to/emails');

// Load any additional extensions
$engine->loadExtension(new \Plates\Extension\Asset('/path/to/public'));
~~~

## Basic usage

~~~.language-php
<?php

// Create a new template
$template = new \Plates\Template($engine);

// Assign a variable to the template
$template->name = 'Jonathan';

// Render the template
echo $template->render('home');

// Render a folder template
$email = $template->render('emails::welcome');
~~~