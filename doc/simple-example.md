---
layout: default
permalink: simple-example/
title: Simple example
---

Simple example
==============

Here is a simple example of how to use Plates. We will assume the following directory stucture:

~~~
`-- path
    `-- to
        `-- templates
            |-- template.phtml
            |-- profile.phtml
~~~

## Render a template

~~~ php
// Create new Plates instance
$templates = League\Plates\Engine::create('/path/to/templates');

// Render a template with the given data
echo $templates->render('profile', ['name' => 'Jonathan']);
~~~

## The page template

<div class="filename">profile.phtml</div>
~~~ php
<?php $v->layout('template', ['title' => 'User Profile']) ?>

<h1>User Profile</h1>
<p>Hello, <?=$v($name) // escape the $name variable ?></p>
~~~

## The layout template

<div class="filename">template.phtml</div>
~~~ php
<html>
<head>
    <title><?=$v($title)?></title>
</head>
<body>

<?=$v->section('content')?>

</body>
</html>
~~~
