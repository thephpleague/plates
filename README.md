Plates
======

Slick native PHP template system that’s fast, extendable and easy to use.

## Highlights

- Native PHP templates
- This is a template system, not a template language
- Namespacing for super simple template paths
- Inheritance and escaping built in
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

## Example templates

### profile.tpl

```php
<? $this->title = 'User Profile' ?>
<? $this->insert('partials/header') ?>

<h1>User Profile</h1>
<p>Hello, <?=$this->e($this->name)?></p>

<? $this->insert('partials/footer') ?>
```

### header.tpl

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$this->title?></title>
    <link rel="stylesheet" href="<?=$this->asset('/css/all.css') ?>" />
</head>

<body>
```

### footer.tpl

```php
</body>
</html>
```

## Inheritance example

### profile.tpl

```php
<? $this->title = 'User Profile' ?>

<? $this->startBlock('content') ?>

    <h1>User Profile</h1>
    <p>Hello, <?=$this->e($this->name)?></p>

<? $this->endBlock() ?>

<? $this->startBlock('sidebar') ?>

    <ul>
        <li><a href="/link">Example Link</a></li>
        <li><a href="/link">Example Link</a></li>
        <li><a href="/link">Example Link</a></li>
        <li><a href="/link">Example Link</a></li>
        <li><a href="/link">Example Link</a></li>
    </ul>

<? $this->endBlock() ?>

<? $this->insert('template') ?>
```

### template.tpl

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$this->title?></title>
    <link rel="stylesheet" href="<?=$this->asset('/css/all.css') ?>" />
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

### ChangeCase.php

```php
<?php

class ChangeCase extends \Plates\Extension\Base
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

### bootstrap.php

```php
<?php

// Load custom extension
$plates->loadExtension(new \ChangeCase());
```

### template.tpl

```php
<? $this->title = 'User Profile' ?>
<? $this->insert('partials/header') ?>

<h1>User Profile</h1>
<p>Hello, <?=$this->uppercase($this->name)?></p>

<? $this->insert('partials/footer') ?>
```
