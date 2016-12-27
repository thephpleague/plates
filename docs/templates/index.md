---
layout: default
permalink: templates/
title: Templates
---

Templates
=========

Plates templates are very simple PHP objects. Generally you'll want to create these using the two factory methods, `make()` and `render()`, in the [engine](/engine/). For example:

~~~ php
// Create new Plates instance
$templates = new League\Plates\Engine('/path/to/templates');

// Render a template in a subdirectory
echo $templates->render('partials/header');

// Render a template
echo $templates->render('profile', ['name' => 'Jonathan']);
~~~

For more information about how Plates is designed to be easily added to your application, see the section on [dependency injection](/engine/#dependency-injection).

## Manually creating templates

It's also possible to create templates manually. The only dependency they require is an instance of the [engine](/engine/) object. For example:

~~~ php
// Create new Plates instance
$templates = new League\Plates\Engine('/path/to/templates');

// Create a new template
$template = new League\Plates\Template\Template($templates, 'profile');

// Render the template
echo $template->render(['name' => 'Jonathan']);

// You can also render the template using the toString() magic method
echo $template;
~~~

## Check if a template exists

When dynamically loading templates, you may need to check if they exist. This can be done using the engine's `exists()` method:

~~~ php
if ($templates->exists('articles::beginners_guide')) {
    // It exists!
}
~~~

You can also run this check on an existing template:

~~~ php
if ($template->exists()) {
    // It exists!
}
~~~

## Get a template path

To get a template path from its name, use the engine's `path()` method:

~~~ php
$path = $templates->path('articles::beginners_guide');
~~~

You can also get the path from an existing template:

~~~ php
$path = $template->path();
~~~
