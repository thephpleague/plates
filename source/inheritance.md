---
layout: layout
title: Inheritance
---

Inheritance
===========

Template inheritance is a really powerful part of Plates. By using [layouts](/layouts) and [sections](/sections), you to can create a base layout that contains all the common sections of your site. Then, when you build your page template, simply define the layout you want to use and your page will automatically be rendered "into it".

The best way to understand template inheritance is by an example:

## The layout template

~~~language-php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$this->title?></title>
</head>

<body>

<div id="content">
    <?=$this->content?>
</div>

<?php if (isset($this->sidebar)): ?>
    <div id="sidebar">
        <?=$this->sidebar?>
    </div>
<?php endif ?>

</body>
</html>
~~~

## The page template

~~~language-php
<?php $this->layout('template') ?>

<?php $this->title = 'User Profile' ?>

<?php $this->start('content') ?>
    <h1>Welcome!</h1>
    <p>Hello <?=$this->e($this->name)?></p>
<?php $this->end() ?>

<?php $this->start('sidebar') ?>
    <ul>
        <li><a href="/link">Example Link</a></li>
        <li><a href="/link">Example Link</a></li>
        <li><a href="/link">Example Link</a></li>
        <li><a href="/link">Example Link</a></li>
        <li><a href="/link">Example Link</a></li>
    </ul>
<?php $this->end() ?>
~~~