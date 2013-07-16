Plates
======

Slick native PHP template system that’s fast, extendable and easy to use.

## Highlights

- Native PHP templates
- This is a template system, not a template language
- Namespacing for super simple template paths
- Layouts, inheritance and escaping built-in
- Really easy to extend using extensions
- Not framework specific, will work with any project
- Composer ready

## Table of contents

- [Getting started](#getting-started)
- [Simple example](#simple-example)
- [Inheritance example](#inheritance-example)
- [Building extensions](#building-extensions)
- [Inserting templates](#inserting-templates)
- [Inheritance](#inheritance)
- [Template syntax](#template-syntax)
- [Template file extensions](#template-file-extensions)

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

// Any any additional, namespaced folders
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

// Render namespaced template
echo $template->render('emails::welcome');
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

## Inheritance example

### profile.tpl

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

### template.tpl

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

<div id="sidebar">
    <?=$this->sidebar?>
</div>

</body>
</html>
```

## Building extensions

Creating extensions couldn't be easier. Simply create a class with a public `$methods` parameter indicating which methods are to be available within a template.

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

To use this extension in your template, call the methods you've made available:

```php
<p>Hello, <?=$this->uppercase($this->firstname)?> <?=$this->lowercase($this->firstname)?>.</p>
```

### Single method extension

Alternatively, you may choose to expose the entire extension object to the template using a single method. This can make your methods more legible, and also reduce the chance of conflicts with other extensions.

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

To use this extension in your template, first call the primary method, then the secondary methods:

```php
<p>Hello, <?=$this->case()->upper($this->firstname)?> <?=$this->case()->lower($this->firstname)?>.</p>
```

### Loading extensions

Once you've created an extension, load it into the `Engine` object in your project bootstrap.

```php
<?php

// Load custom extension
$plates->loadExtension(new \ChangeCase());
```

## Inserting templates

Inserting (or including) another template into the current template is done using the `insert()` method:

```php
<? $this->insert('header') ?>
```

The `insert()` method also works with folder namespaces: 

```php
<? $this->insert('partials::header') ?>
```

## Inheritance

There are four methods available when using inheritance:

### layout()

This defines the layout template which the current template will implement. It can be placed anywhere in your template, but is probably best found near the top. This method works with folder namespacing as well.

```php
<? $this->layout('template') ?>
```

### start() and end()

The `start()` and `end()` methods allow you to build sections (or blocks) of content within your template, but instead of them being rendered directly, they are placed into variables for use elsewhere (ie. in your layout). You define the name of this variable in the `start('variable_name')` method.

In the following example, the content between the `start()` and `end()` methods will be rendered into a variable called `$this->content`.

```php
<? $this->start('content') ?>

    <h1>Welcome!</h1>
    <p>Welcome, <?=$this->e($this->name)?></p>

<? $this->end() ?>
```

### child()

The `child()` method is a special function only available in the template layout. It will return all outputted content from a child template that hasn't been defined in a section. This can be helpful if you prefer to not use sections, but still want to use the layout feature.

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

## Template syntax

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

## Template file extensions

Plates does not enforce a specific template file extension. By default it assumes `.php`. This file extension is automatically appended to your template names when rendered. You are welcome to change the default extension using one of the two methods below.

### Constructor method

```php
<?php

// Create new plates engine and set the file default extension to ".tpl"
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