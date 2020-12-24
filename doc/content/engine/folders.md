+++
title = "Folders"
linkTitle = "Engine Folders"
[menu.main]
parent = "engine"
weight = 3
+++

Folders make it really easy to organize and access your templates. Folders allow you to group your templates under different namespaces, each of which having their own file system path.

## Creating folders

To create folders, use the `addFolder()` method:

~~~ php
// Create new Plates instance
$templates = new League\Plates\Engine();

// Add folders
$templates->addFolder('admin', '/path/to/admin/templates');
$templates->addFolder('emails', '/path/to/email/templates');
~~~

## Using folders

To use the folders you created within your project simply append the folder name with two colons before the template name. For example, to render a welcome email:

~~~ php
$email = $templates->render('emails::welcome');
~~~

This works with template functions as well, such as layouts or nested templates. For example:

~~~ php
<?php $this->layout('shared::template') ?>
~~~

## Folder fallbacks

When enabled, if a folder template is missing, Plates will automatically fallback and look for a template with the **same** name in the default folder. This can be helpful when using folders to manage themes. To enable fallbacks, simply pass `true` as the third parameter in the `addFolders()` method.

~~~ php
// Create new Plates engine
$templates = new \League\Plates\Engine('/path/to/default/theme');

// Add themes
$templates->addFolder('theme1', '/path/to/theme/1', true);
$templates->addFolder('theme2', '/path/to/theme/2', true);
~~~