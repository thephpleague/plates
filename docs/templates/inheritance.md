---
layout: default
permalink: templates/inheritance/
title: Inheritance
---

Inheritance
===========

By combining [layouts](/templates/layouts/) and [sections](/templates/sections/), Plates allows you to "build up" your pages using predefined sections. This is best understand using an example:


## Inheritance example

The following example illustrates a pretty standard website. Start by creating a site template, which includes your header and footer as well as any predefined content [sections](/templates/sections/). Notice how Plates makes it possible to even set default section content, in the event that a page doesn't define it.

<div class="filename">template.php</div>
~~~ php
<html>
<head>
    <title><?=$this->e($title)?></title>
</head>
<body>

<img src="logo.png">

<div id="page">
    <?=$this->section('page')?>
</div>

<div id="sidebar">
    <?php if ($this->section('sidebar')): ?>
        <?=$this->section('sidebar')?>
    <?php else: ?>
        <?=$this->fetch('default-sidebar')?>
    <?php endif ?>
</div>

</body>
</html>
~~~

With the template defined, any page can now "implement" this [layout](/templates/layouts/). Notice how each section of content is defined between the `start()` and `end()` functions.

<div class="filename">profile.php</div>
~~~ php
<?php $this->layout('template', ['title' => 'User Profile']) ?>

<?php $this->start('page') ?>
    <h1>Welcome!</h1>
    <p>Hello <?=$this->e($name)?></p>
<?php $this->stop() ?>

<?php $this->start('sidebar') ?>
    <ul>
        <li><a href="/link">Example Link</a></li>
        <li><a href="/link">Example Link</a></li>
        <li><a href="/link">Example Link</a></li>
        <li><a href="/link">Example Link</a></li>
        <li><a href="/link">Example Link</a></li>
    </ul>
<?php $this->stop() ?>
~~~