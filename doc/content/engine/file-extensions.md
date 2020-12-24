+++
title = "File Extensions"
linkTitle = "Engine File Extensions"
[menu.main]
parent = "engine"
weight = 2
+++

Plates does not enforce a specific template file extension. By default it assumes `.php`. This file extension is automatically appended to your template names when rendered. You are welcome to change the default extension using one of the following methods.

## Constructor method

~~~ php
// Create new engine and set the default file extension to ".tpl"
$template = new League\Plates\Engine('/path/to/templates', 'tpl');
~~~

## Setter method

~~~ php
// Sets the default file extension to ".tpl" after engine instantiation
$template->setFileExtension('tpl');
~~~

## Manually assign

If you prefer to manually set the file extension, simply set the default file extension to `null`.

~~~ php
// Disable automatic file extensions
$template->setFileExtension(null);

// Render template
echo $templates->render('home.php');
~~~