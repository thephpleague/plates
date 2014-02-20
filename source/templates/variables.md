---
layout: layout
title: Variables
---

Variables
=========

To assign variables to your templates just add them as parameters to your template object. This can be done both within the template itself and from your application code (ie. controllers). Variables can be whatever you want—strings, arrays, objects, etc.

## Assign variables within your application

Assigning variables from within your application code, such as a controller, is the most common way to set template variables.

~~~language-php
<?php

// Assign data as object properties
$template->name = 'Jonathan';
$template->friends = array('Amy', 'Neil', 'Joey');

// Or in bulk using the data() method
$template->data(
    [
        'name' => 'Jonathan',
        'friends' => array('Amy', 'Neil', 'Joey')
    ]
);

// Or in bulk when using the render() method
echo $template->render('profile',
    [
        'name' => 'Jonathan',
        'friends' => array('Amy', 'Neil', 'Joey')
    ]
);
~~~

## Assign variables within your templates

Assigning variables within your templates can be very helpful for sharing information with nested and layout templates. A good example is a website’s page title. It makes sense to define this within your page template, but it will actually be rendered in your layout template between the `<title></title>` tags.

~~~language-php
<?php $this->title = 'Home' ?>
~~~

## Variable visibility

It’s important to note that template variables are visible to [layout](/templates/layouts/) and [nested](/templates/nesting/) templates as well. This can be very helpful, as seen in the previous page title example.