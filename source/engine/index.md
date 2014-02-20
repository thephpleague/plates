---
layout: layout
title: The Engine
---

The Engine
==========

Plates uses a central object called the `Engine`, which is used to store the environment configuration and extensions. It helps decouple your templates from the file system and other dependencies. For example, if you want to change the folder where your templates are stored, you can do so by simply changing the path in one location.

## Basic usage

~~~.language-php
<?php

// Create new Plates engine
$engine = new \League\Plates\Engine('/path/to/templates');

// Add any any additional folders
$engine->addFolder('emails', '/path/to/emails');

// Load any additional extensions
$engine->loadExtension(new \League\Plates\Extension\Asset('/path/to/public'));

// Create a new template
$template = new \League\Plates\Template($engine);
~~~