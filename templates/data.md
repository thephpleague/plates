---
layout: default
permalink: templates/data/
title: Data
---

Data
====

It's very common to share application data (variables) with a template. Data can be whatever you want: strings, arrays, objects, etc. Plates allows you set both template specific data as well as shared template data.

## Assign data

Assigning data is done from within your application code, such as a controller. There are a number of ways to assign the data, depending on how you structure your objects.

~~~ php
// Create new Plates instance
$templates = new League\Plates\Engine('/path/to/templates');

// Assign via the engine's render method
echo $templates->render('profile', ['name' => 'Jonathan']);

// Assign via the engine's make method
$template = $templates->make('profile', ['name' => 'Jonathan']);

// Assign directly to a template object
$template = $templates->make('profile');
$template->data(['name' => 'Jonathan']);
~~~

## Accessing data

Template data is available as locally scoped variables at the time of rendering. Continuing with the example above, here is how you would [escape](/templates/escaping/) and output the "name" value in a template:

~~~ php
<p>Hello <?=$this->e($name)?></p>
~~~

<p class="message-notice">Prior to Plates 3.0, variables were accessed using the <code>$this</code> pseudo-variable. This is no longer possible. Use the locally scoped variables instead.</p>

## Preassigned and shared data

If you have data that you want assigned to a specific template each time that template is rendered throughout your application, the `addData()` function can help organize that code in one place.

~~~ php
$templates->addData(['name' => 'Jonathan'], 'emails::welcome');
~~~

You can pressaign data to more than one template by passing an array of templates:

~~~ php
$templates->addData(['name' => 'Jonathan'], ['login', 'template']);
~~~

To assign data to ALL templates, simply omit the second parameter:

~~~ php
$templates->addData(['name' => 'Jonathan']);
~~~

Keep in mind that shared data is assigned to a template when it's first created, meaning any conflicting data assigned that's afterwards to a specific template will overwrite the shared data. This is generally desired behavior.