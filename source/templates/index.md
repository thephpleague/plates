---
layout: layout
title: Templates
---

Templates
=========

Plates templates are very simple PHP objects. As a result, all [variables](/templates/variables/) and functions are accessed through the `$this` pseudo-variable. The only dependency they require is an instance of the [engine](/engine/) object.

## Basic usage

~~~.language-php
<?php

// Create a new template
$template = new \League\Plates\Template($engine);

// Assign a variable to the template
$template->name = 'Jonathan';

// Render the template
echo $template->render('home');

// Render a folder template
$email = $template->render('emails::welcome');
~~~

## Template factory

Instead of manually instantiating templates, you can also use the engine’s `makeTemplate()` factory method. This approach makes it easy to use dependency injection to inject the engine into other objects and then create new templates. This in turn makes these objects easier to test and mock.

~~~.language-php
<?php

// Create a new template
$template = $engine->makeTemplate();
~~~

## Checking if a template exists

When dynamically loading templates, you may need to check if a template exists. This can be done using the engine’s `pathExists()` method.

~~~.language-php
<?php

if ($engine->pathExists('articles::beginners_guide')) {
    // It exists!
}
~~~