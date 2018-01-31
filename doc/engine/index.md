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

// register any custom extensions
$plates->register(new MyCustomExtension());

echo $plates->render('home', ['title' => 'Home Page']);
```

## Creating the Engine

The Engine comes with several constructors that offer varying degrees of customization.

### create($baseDir, $ext = null)

```php
$plates = League\Plates\Engine::create('/path/to/templates', $ext = 'phtml');
```

Creates the plates Engine sets the base dir and extension and loads all of the standard extensions.

### createWithConfig(array $config = [])

```php
$plates = League\Plates\Engine::createWithConfig([
    'base_dir' => '/path/to/templates',
    'ext' => 'phtml',
    'escape_encoding' => 'UTF-8'
]);
```

This creates the engine, loads the standard extensions and then calls `addConfig` on the given config.

### \_\_construct(League\Plates\Util\Container $container = null)

```php
$plates = new League\Plates\Engine();
```

Creates an empty instance of the plates engine without any extensions or configuration loaded. Optionally, you can pass in an instance of the `League\Plates\Util\Container`.

**Note** This is for advanced usage only, typically you'll want to use the other static constructors.

## Configuring the Engine

Once you've created an engine, you can then configure it by loading extensions and calling extension methods.

### Registering Extensions

You can register extensions via:

```php
$plates->register(new MyAwesomeExtension());
```

### Config

You can define config values via `addConfig`.

```php
$plates->addConfig([
    'config_item' => 'val'
]);
```

This merges your defined config with the existing configuration defintions. Extensions define a default set of config values which can configured to customize each extension. You'll want to read through each extension documentation to see the configuration values they support.

### Engine Methods

Extensions can also define methods on the Engine which can be used for configuration. You can define methods with `addMethods`:

```php
$plates->addMethods([
    'acmeFn' => function(Engine $plates, $arg1, $arg2 = null) {
        // do some stuff to the engine
    }
])
```

You can then call the `acmeFn` function on the Engine. It shares the same signature as the method definition, but without the Engine method as the first argument.

```php
$plates->acmeFn('arg1'); // arg2 is optional in this instance.
```

Here are a few examples of Engine Methods which are used for configuring.

```php
// addFolder is defined by the FolderExtension
$plates->addFolder('folderName', 'folderPathPrefix');
// addGlobals is defined by the DataExtension
$plates->addGlobals([
    'isLoggedIn' => true,
    'siteName' => 'Acme Site'
]);
```

In this example, we've defined a folder and two globals for our Engine.

You can check each extensions' documentation to see the different engine methods they define.

## Rendering Templates

The engine provides the `render(string $name, array $data, array $attributes = []): string` for rendering a template into a string.

This is just a convenience function over:

```
$plates->getContainer()->get('renderTemplate')->render(new Template($name, $data, $attributes));
```

The `RenderTemplate` interface is what is actually responsible for rendering template instances into strings.
