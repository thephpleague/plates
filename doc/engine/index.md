---
layout: default
permalink: engine/
title: The Engine
---

The Engine
==========

The Engine is responsible for configuring the Plates system and is the entry point for rendering templates. It wraps an IoC container which stores anything and everything from global template data, functions, render templates, and more. All of your extension and template configuration is stored in one place which makes configuring plates simple and manageable.

## Basic Usage

```php
$plates = League\Plates\Engine::create('/path/to/templates');

// optionally add/merge config values
$plates->addConfig([
    'render_context_var_name' => 'v',
]);

// optionally call an extension defined method to further configure the engine and the extensions
$plates->addGlobal('siteName', 'Acme');

// render any custom extensions
$plates->register(new MyCustomExtension());

echo $plates->render('home', ['title' => 'Home Page']);
```

## Creating Engines

Todo - show examples of the several different constructor methods
