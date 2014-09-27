---
layout: default
permalink: templates/inheritance/
title: Inheritance
---

Inheritance
===========

By combining [layouts](/templates/layouts/) and [sections](/templates/sections/) you to can create a base layout that contains all the common sections of your site. Then, when you build your page template, simply define the layout you want to use and your page will automatically be rendered "into it". The best way to understand template inheritance is by an example:

## The layout template

<div class="filename">template.php</div>
~~~ php
<html>
<head>
    <title><?=$title?></title>
</head>
<body>

<div id="content">
    <?=$this->section('content')?>
</div>

<?php if ($this->section('sidebar')): ?>
    <div id="sidebar">
        <?=$this->section('sidebar')?>
    </div>
<?php endif ?>

</body>
</html>
~~~

## The page template

<div class="filename">profile.php</div>
~~~ php
<?php $this->layout('template', ['title' => 'User Profile']) ?>

<?php $this->start('content') ?>

    <h1>Welcome!</h1>
    <p>Hello <?=$name?></p>

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