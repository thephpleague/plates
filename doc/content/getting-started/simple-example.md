+++
title = "Simple Example"
[menu.main]
parent = "getting-started"
weight = 2
+++

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

{{< code-filename profile.php >}}
~~~ php
<?php $this->layout('template', ['title' => 'User Profile']) ?>

<h1>User Profile</h1>
<p>Hello, <?=$this->e($name)?></p>
~~~

## The layout template

{{< code-filename template.php >}}
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