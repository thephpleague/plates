Plates
======

The native PHP template system that's fast, easy to extend and natural to use.


## Highlights

- Native PHP templates
- This is a template system, not a template language
- Encourages use of existing PHP functions
- Folders for super simple template paths
- Powerful layouts and inheritance tools
- Really easy to extend using extensions
- Decoupled design makes templates easy to test and mock
- Not framework specific, will work with any project
- Composer ready


## About

Plates was created by [Jonathan Reinink](https://twitter.com/reinink), and was inspired by the excellent [Twig](http://twig.sensiolabs.org/) template engine. It aspires to bring modern template language functionality to native PHP templates.


## Table of contents

- [Getting started](#getting-started)
- [Simple example](#simple-example)
- [The engine](#the-engine)
- [File extensions](#file-extensions)
- [Folders](#folders)
- [Inserting templates](#inserting-templates)
- [Template inheritance](#template-inheritance)
- [Building extensions](#building-extensions)
- [Escape extension](#escape-extension)
- [Batch extension](#batch-extension)
- [URI extension](#uri-extension)
- [Asset extension](#asset-extension)
- [Recommended template syntax](#recommended-template-syntax)


## Getting started

### Installation

Plates is available via Composer:

```json
{
    "require": {
        "reinink/plates": "1.*"
    }
}
```

### Setup

```php
<?php

// Include Composer autoloader
require 'vendor/autoload.php';

// Create new plates engine
$plates = new \Plates\Engine('/path/to/templates');

// Load any additional extensions
$plates->loadExtension(new \Plates\Extension\Asset('/path/to/public'));

// Any any additional folders
$plates->addFolder('emails', '/path/to/emails');
```

### Basic usage

```php
<?php

// Create a new template
$template = new \Plates\Template($plates);

// Assign a variable to the template
$template->name = 'Jonathan';

// Render the template
echo $template->render('home');

// Render folder template
$email = $template->render('emails::welcome');
```

## Simple example

### profile.tpl

```php
<? $this->layout('template') ?>

<? $this->title = 'User Profile' ?>

<h1>User Profile</h1>
<p>Hello, <?=$this->e($this->name)?></p>
```

### template.tpl

```php
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
```


## The engine

Plates uses a central object called the `Engine`, which is used to store the environment configuration and extensions. It helps decouple your templates from the file system and other dependencies. For example, if you want to change the folder where your templates are stored, you can do so by simply changing the path in one location.

Templates themselves are very simple objects. The only dependency they require is an instance of the engine object. This design makes it easy to use dependency injection to inject your templates into other objects, which in return makes them easier to test and mock.


## File extensions

Plates does not enforce a specific template file extension. By default it assumes `.php`. This file extension is automatically appended to your template names when rendered. You are welcome to change the default extension using one of the following methods.

### Constructor method

```php
<?php

// Create new plates engine and set the default file extension to ".tpl"
$plates = new \Plates\Engine('/path/to/templates', 'tpl');
```

### Setter method

```php
<?php

// Sets the default file extension to ".tpl" after engine instantiation
$plates->setFileExtension('tpl');
```

### Manually assign file extension

If you'd prefer to manually set the template file extension (ie. `$template->render('home.tpl')`), simply set the default file extension to `null`:

```php
<?php

$plates->setFileExtension(null);
```

## Folders

Folders make it really easy to organize and access your various template groups. Folders allow to group your templates under different namespaces which each have their own template paths.

### Creating folders

To create folders, use the engine's `addFolder()` function:

```php
<?php

$plates->addFolder('admin', '/path/to/admin/templates');
$plates->addFolder('emails', '/path/to/email/templates');
```

### Using folders

To use the folders you created within your project simply append the folder name with two colons before the template name. This works with all template definition functions: `render()`, `insert()` and `layout()`. For example, to render a welcome email:

```php
$email = $template->render('emails::welcome');
```


## Inserting templates

Inserting (or including) another template into the current template is done using the `insert()` function:

```php
<? $this->insert('header') ?>

<p>Your content.</p>

<? $this->insert('footer') ?>
```

The `insert()` function also works with folders: 

```php
<? $this->insert('partials::header') ?>
```


## Template inheritance

Template inheritance is a really powerful part of Plates. It allows you to create a base template (like a website layout) that contains all the common sections of your site (see `template.tpl` below). Then, when you create your actual page template (see `profile.tpl`), you tell Plates the layout you want to use and it will automatically render the page inside your layout using the sections you define.

### Inheritance example

While template inheritance sounds complicated, it really isn't. The best way to understand it is by an example:

#### template.tpl

```php
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

<? if ($this->sidebar): ?>
    <div id="sidebar">
        <?=$this->sidebar?>
    </div>
<? endif ?>

</body>
</html>
```

#### profile.tpl

```php
<? $this->layout('template') ?>

<? $this->title = 'User Profile' ?>

<? $this->start('content') ?>
    <h1>Welcome!</h1>
    <p>Welcome, <?=$this->e($this->name)?></p>
<? $this->end() ?>

<? $this->start('sidebar') ?>
    <ul>
        <li><a href="/link">Example Link</a></li>
        <li><a href="/link">Example Link</a></li>
        <li><a href="/link">Example Link</a></li>
        <li><a href="/link">Example Link</a></li>
        <li><a href="/link">Example Link</a></li>
    </ul>
<? $this->end() ?>
```

### Layouts

The `layout()` function allows you to define a layout template that the current template will "implement". It can be placed anywhere in your template, but is probably best found near the top. This function works with folders as well.

Note: Your actual template will be rendered before the layout, which is quite helpful as you can assign variables (ie. `<? $this->title = 'User Profile' ?>`) that will be available when the layout is rendered.

```php
<? $this->layout('template') ?>
```

### Sections

The `start()` and `end()` functions allow you to build sections (or blocks) of content within your template, but instead of them being rendered directly, they are placed into variables for use elsewhere (ie. in your layout). You define the name of this variable in the `start('variable_name')` function.

In the following example, the content between the `start()` and `end()` functions will be rendered into a variable called `$this->content`.

```php
<? $this->start('content') ?>

    <h1>Welcome!</h1>
    <p>Welcome, <?=$this->e($this->name)?></p>

<? $this->end() ?>
```

### Content outside of sections

If you prefer to not use sections, but still want to use the layout feature, you need a way to access the rendered page template within your layout template. The `child()` function is a special function only available in the layout template, which will return all outputted content from the child template that hasn't been defined in a section. Here is an example:

#### profile.tpl

```php
<? $this->layout('template') ?>

<p>Hello World!</p>
```

#### template.tpl

```php
<!DOCTYPE html>
<body>

<!-- Will output: <p>Hello World!</p> -->
<?=$this->child()?>

</body>
</html>
```


## Building extensions

Creating extensions couldn't be easier, and can really make Plates sing for your specific project. Simply create a class with a public `$methods` parameter indicating which functions in that class are to be available within your templates.

### Simple extensions example

```php
<?php

class ChangeCase
{
    public $methods = array('uppercase', 'lowercase');
    public $engine;
    public $template;

    public function uppercase($var)
    {
        return strtoupper($var);
    }

    public function lowercase($var)
    {
        return strtolower($var);
    }
}
```

To use this extension in your template, call the functions you've made available:

```php
<p>Hello, <?=$this->uppercase($this->firstname)?> <?=$this->lowercase($this->firstname)?>.</p>
```

### Single method extensions

Alternatively, you may choose to expose the entire extension object to the template using a single method. This can make your template functions more legible, and also reduce the chance of conflicts with other extensions.

```php
<?php

class ChangeCase
{
    public $methods = array('case');
    public $engine;
    public $template;

    public function case()
    {
        return $this;
    }

    public function upper($var)
    {
        return strtoupper($var);
    }

    public function lower($var)
    {
        return strtolower($var);
    }
}
```

To use this extension in your template, first call the primary function, then the secondary functions:

```php
<p>Hello, <?=$this->case()->upper($this->firstname)?> <?=$this->case()->lower($this->firstname)?>.</p>
```

### Loading extensions

Once you've created an extension, load it into the `Engine` object using the `loadExtension()` function.

```php
<?php

// Load custom extension
$plates->loadExtension(new \ChangeCase());
```


### Syntax example

Here is an example of a template that complies with the above syntax rules.

```php
<? $this->layout('template') ?>

<? $this->title = 'User Profile' ?>

<h1>Welcome!</h1>
<p>Welcome, <?=$this->e($this->name)?></p>

<h2>Friends</h2>
<ul>
    <? foreach($this->friends as $friend): ?>
        <li><a href="/profile/<?=$this->e($friend->id)?>"><?=$this->e($friend->name)?></a></li>
    <? endforeach ?>
</ul>

<? if ($this->invitations): ?>
    <h2>Invitations</h2>
    <p>You have some friend invites!</p>
<? endif ?>
```


## Escape extension

The escape extension comes packaged with Plates and is enabled by default. Escaping is a form of [data filtering](http://www.phptherightway.com/#data_filtering) which sanitizes unsafe, user supplied input prior to outputting it as HTML. Plates provides two shortcuts to the `htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')` function.

```php
<?=$this->escape($this->var)?>
<?=$this->e($this->var)?>
```

### Warning about escaping variables

Probably the biggest issue with native PHP templates is the inability to auto-escape variables. Template languages like Twig and Smarty can identify "echoed" variables during a parsing stage and then auto-escape them. This cannot be done in native PHP as the language does not offer overloading functionality for it's output functions (ie. `print` and `echo`).

Don't worry, all hope is not lost. Escaping can still be done safely, it just means you are responsible for manually escaping each variable on output. Consider creating a snippet for one of the above, built-in escaping functions to make this process easier.


## Batch extension

Sometimes you need to apply more than function to a variable in your templates. This can become somewhat illegible. The batch extension helps by allowing you to apply multiple extension functions AND native PHP functions to a variable at one time.

### Batch example

Example without using batch

```php
<p>Welcome, <?=strtoupper($this->escape(strip_tags($this->name)))?></p>
```

Example using batch

```php
<p>Welcome, <?=$this->batch($this->name, 'strip_tags|e|strtoupper')?></p>
```

### How the batch extension works

The batch extension works well for functions that accept one parameter, modify it, and then return it. It's also important to note that it executes functions left to right. It will also favour extension functions over native PHP functions if there are conflicts.

```php
<!-- Will output: JONATHAN -->
<?=$this->batch('Jonathan', 'e|strtolower|strtoupper')?>

<!-- Will output: jonathan -->
<?=$this->batch('Jonathan', 'e|strtoupper|strtolower')?>
```


## URI extension

The URI extension is designed to make URI checks within templates easier. The most common use is marking the current page in a menu as "selected". It only has one function, `uri()`, but can do a number of helpful tasks depending on the parameters passed to it.

### Installing the URI extension

The URI extension comes packaged with Plates but is not enabled by default, as it requires an extra parameter passed to it at instantiation.

```php
<?php

// Load URI extension
$plates->loadExtension(new \Plates\Extension\URI($_SERVER['PATH_INFO']));
```

### Using the URI extension

Get the whole URI.

```php
<? $this->uri() ?>
```

Get a specified segment of the URI.

```php
<? $this->uri(1) ?>
```

Check if a specific segment of the URI (first parameter) equals a given string (second parameter). Returns `true` on success or `false` on failure.

```php
<? $this->uri(1, 'home') ?>
```

Check if a specific segment of the URI (first parameter) equals a given string (second parameter). Returns string (third parameter) on success or `false` on failure.

```php
<? $this->uri(1, 'home', 'success') ?>
```

Check if a specific segment of the URI (first parameter) equals a given string (second parameter). Returns string (third parameter) on success or string (fourth parameter) on failure.

```php
<? $this->uri(1, 'home', 'success', 'fail') ?>
```

Check if a regular expression string matches the current URI. Returns `true` on success or `false` on failure.

```php
<? $this->uri('/home') ?>
```

Check if a regular expression string (first parameter) matches the current URI. Returns string (second parameter) on success or `false` on failure.

```php
<? $this->uri('/home', 'success') ?>
```

Check if a regular expression string (first parameter) matches the current URI. Returns string (second parameter) on success or string (third parameter) on failure.

```php
<? $this->uri('/home', 'success', 'fail') ?>
```

### URI extension example

```php
<ul>
    <li <?=$this->uri('/', 'class="selected"')?>><a href="/">Home</a></li>
    <li <?=$this->uri('/about', 'class="selected"')?>><a href="/about">About</a></li>
    <li <?=$this->uri('/products', 'class="selected"')?>><a href="/products">Products</a></li>
    <li <?=$this->uri('/contact', 'class="selected"')?>><a href="/contact">Contact</a></li>
</ul>
```


## Asset extension

The asset extension can be used to quickly create "cache busted" asset URLs in your templates. This is particularly helpful for aggressively cached files that can potentially change in the future, such as CSS files, JavaScript files and images. It works by appending the timestamp of the file's last update to its URL. For example, `/css/all.css` becomes `/css/all.1373577602.css`. As long as the file does not change, the timestamp remains the same and caching occurs. However, if the file is changed, a new URL is automatically generated with a new timestamp, and visitors receive the new file.

### Installing the asset extension

The asset extension comes packaged with Plates but is not enabled by default, as it requires extra parameters passed to it at instantiation.

```php
<?php

// Load asset extension
$plates->loadExtension(new \Plates\Extension\Asset('/path/to/public/assets/', true));
```

The first constructor parameter is the file system path of the assets directory. The second is an optional `boolean` parameter that if set to true uses the filename caching method (ie. `file.1373577602.css`) instead of the default query string method (ie. `file.css?v=1373577602`).

### Filename caching

To make filename caching work, some URL rewriting is required:

#### Apache example
```
<IfModule mod_rewrite.c>
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule ^(.+)\.(\d+)\.(js|css|png|jpg|gif)$ $1.$3 [L]
</IfModule>
```

#### Nginx example

```
location ~* (.+)\.(?:\d+)\.(js|css|png|jpg|jpeg|gif)$ {
   try_files $uri $1.$2;
}
```

### Using the asset extension

```php
<!DOCTYPE html>
<head>
    <title>Asset Extension Example</title>
    <link rel="stylesheet" href="<?=$this->asset('/css/all.css')?>" />
</head>

<body>

<img src="<?=$this->asset('/img/logo.png')?>">

</body>
</html>
```

## Recommended template syntax

While the actual syntax you use in your templates is entirely your choice (it's just PHP after all), we recommend the following syntax guidelines to help keep templates clean and legible.

- Always use HTML with inline PHP. Never use blocks of PHP.
- Always escape potentially dangerous variables prior to outputting using the built-in escape functions. Ie. `$this->e($var)`
- If [short tags](http://www.php.net/manual/en/ini.core.php#ini.short-open-tag) are enabled, which they almost always are, use `<?`, `<?=` and `?>`.
- Avoid using the full `<?php` tag, unless short tags are disabled.
- Always use the [alternative syntax for control structures](http://php.net/manual/en/control-structures.alternative-syntax.php), which are designed to make templates more legible.
- Never use PHP curly brackets.
- Only ever have one statement in a each PHP tag.
- Avoid using semicolons. They are not needed when there is only one statement per PHP tag.
- Never use the `use` operator. Templates should not be interacting with classes in this way.
- Never use the `for`, `while` or `switch` control structures. Instead use `if` and `foreach`.
- Other than templates variables, avoid variable assignment.