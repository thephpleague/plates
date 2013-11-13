Simple example
==============

## Within your controller

~~~.language-php
<?php

// Create a new template
$template = new \Plates\Template($engine);

// Assign a variable to the template
$template->name = 'Jonathan';

// Render the template
echo $template->render('profile');
~~~

## The page template

~~~.language-php
<?php $this->layout('template') ?>

<?php $this->title = 'User Profile' ?>

<h1>User Profile</h1>
<p>Hello, <?=$this->e($this->name)?></p>
~~~

## The layout template

~~~.language-php
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