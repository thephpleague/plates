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

## Extensions example

Creating extensions couldn't be easier. Simply create a class with a public `$methods` paramater indicating which methods are availble within a template.

### Simple example extension

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

To use this extension in your template, simply call the methods you made available:

```php
<p>Hello, <?=$this->uppercase($this->firstname)?> <?=$this->lowercase($this->firstname)?>.</p>
```

### Single method extension

Alternatively, you may choose to expose the entire extension instance to the template using a single method.

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
<p>Hello, <?=$this->case->upper($this->firstname)?> <?=$this->case->lower($this->firstname)?>.</p>
```

### Loading extensions

Once you've created your extension, simply load it into the `Engine` object in your project bootstrap.

```php
<?php

// Load custom extension
$plates->loadExtension(new \ChangeCase());
```

## Inserting templates

Inserting (or including) another template into the current template is easily done using the `insert()` method:

```php
<? $this->insert('header') ?>
```

The `insert()` method even works with folder namespaces: 

```php
<? $this->insert('partials::header') ?>
```

## Inheritance

There are four methods available when using inheritance:

### layout('template')

This defines the layout template which the current template will implement. It should be placed at the top of the template. This method works with folder namespacing as well.

```php
<? $this->layout('template') ?>
```

### start('variable_name') and end()

The `start()` and `end()` methods allow you to build sections (or blocks) of content within your template, but instead of them being rendered directly, they are placed into variables for use elsewhere (ie. in your layout template). You define the name of this variable in the `start()` method.

In the following example, the content between the `start()` and `end()` method will be rendered into a variable called `$this->content`.

```php
<? $this->start('content') ?>

    <h1>Welcome!</h1>
    <p>Welcome, <?=$this->e($this->name)?></p>

<? $this->end() ?>
```

### child()

The `child()` method is a special function only available in the template layout. It will return all outputted content from a child template that hasn't been defined in a section. This can be helpful if you prefer to not use sections, but still want to use the layout features.

### profile.tpl

```php
<? $this->layout('template') ?>

<p>Hello World!</p>
```

### template.tpl

```php
<!DOCTYPE html>
<body>

<?=$this->child()?>
<!-- Will output: <p>Hello World!</p> -->

</body>
</html>
```