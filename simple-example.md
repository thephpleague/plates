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
            |-- template.php
            |-- profile.php
~~~

## Within your controller

~~~ php
// Create new Plates instance
$templates = new League\Plates\Engine('/path/to/templates');

// Render a template
echo $templates->render('profile', ['name' => 'Jonathan']);
~~~

## The page template

<div class="filename">profile.php</div>
~~~ php
<?php $this->layout('template', ['title' => 'User Profile']) ?>

<h1>User Profile</h1>
<p>Hello, <?=$this->e($name)?></p>
~~~

## The layout template

<div class="filename">template.php</div>
~~~ php
<html>
<head>
    <title><?=$this->e($title)?></title>
</head>
<body>

<?=$this->section('content')?>

</body>
</html>
~~~