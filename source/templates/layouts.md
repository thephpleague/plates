---
layout: layout
title: Variables
---

Layouts
=======

The `layout()` function allows you to define a layout template that the current template will implement. It can be placed anywhere in your template.

## The page template

~~~.language-php
<?php $this->layout('template') ?>

<?php $this->title = 'User Profile' ?>

<h1>User Profile</h1>
<p>Hello, <?=$this->e($this->name)?></p>
~~~

This function works with [folders](/engine/folders/) as well:

~~~language-php
<?php $this->insert('shared::template') ?>
~~~

## The layout template

To access the rendered template content within your template, use the `child()` function. This will return all outputted content from the child template that hasn’t been defined in a [section](/templates/sections/).

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

## Rendering order

Your template will be rendered before the layout, which is quite helpful as you can assign variables (ie. `<?php $this->title = 'User Profile' ?>`) that will then be available when the layout is rendered.