---
layout: layout
title: Folders
---

Folders
=======

Folders make it really easy to organize and access your templates. Folders allow you to group your templates under different namespaces, each of which having their own file system path.

## Creating folders

To create folders, use the engine’s `addFolder()` function:

~~~.language-php
<?php

$engine->addFolder('admin', '/path/to/admin/templates');
$engine->addFolder('emails', '/path/to/email/templates');
~~~

## Using folders

To use the folders you created within your project simply append the folder name with two colons before the template name. This works with all template definition functions: `render()`, `insert()` and `layout()`. For example, to render a welcome email:

~~~.language-php
$email = $template->render('emails::welcome');
~~~