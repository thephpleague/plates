---
layout: layout
title: Simple example
---

Simple example
==============

Here is a simple example of how to use Plates. We will assume the following directory stucture:

~~~
`-- path
    `-- to
        `-- templates
            |-- template.php
            |-- profile.php
~~~

## Within your controller

~~~.language-php
<?php

// Create new Plates engine
$engine = new \League\Plates\Engine('/path/to/templates');

// Create a new template
$template = new \League\Plates\Template($engine);

// Assign a variable to the template
$template->name = 'Jonathan';

// Render the template
echo $template->render('profile');
~~~

## The page template

~~~.language-php
<!-- profile.php -->

<?php $this->layout('template') ?>

<?php $this->title = 'User Profile' ?>

<h1>User Profile</h1>
<p>Hello, <?=$this->e($this->name)?></p>
~~~

## The layout template

~~~.language-php
<!-- template.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$this->title?></title>
</head>

<body>

<?=$this->child()?>

</body>
</html>
~~~