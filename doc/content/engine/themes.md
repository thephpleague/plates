+++
title = "Themes"
linkTitle = "Engine Themes"
[menu.main]
parent = "engine"
weight = 6
  [menu.main.params]
  badge = "v3.5"
+++

Themes provide an alternative to template path resolution that allow for a holistic approach to template overrides and fallbacks.

## Usage

Given an engine configuration like:

```php
use League\Plates\{Engine, Template\Theme};

$plates = Engine::fromTheme(Theme::hierarchy([
    Theme::new('/templates/main', 'Main'), // parent
    Theme::new('/templates/user', 'User'), // child
    Theme::new('/templates/seasonal', 'Seasonal'), // child2
]));
```

And a file structure like:

```
templates/
  main/
    layout.php
    home.php
    header.php
  user/
    layout.php
    header.php
  seasonal/
    header.php
```

The following looks ups, *regardless of where they are called from*, would resolve to the following files:

```php
$templates->render('home'); // templates/main/home.php
$templates->render('layout'); // templates/user/layout.php
$templates->render('header'); // templates/seasonal/header.php
```

All paths are resolved from the last child to the first parent allowing a hierarchy of overrides.

## Differences from Directory and Folders

This logic is used **instead of** the directories and folders feature since they are distinct in nature, and combining the logic isn't obvious on how the features should stack.

Creating an engine with one theme is functionally equivalent to using just a directory with no folders.

The fallback functionality is a bit different however since with folders, it's *opt in*, you need to prefix the template name with the folder name. With themes, all template names implicitly will be resolved and fallback according to the hierarchy setup.

## Additional Customization

This functionality is powered by the `League\Plates\Template\ResolveTemplatePath` interface. If you'd prefer a more complex or specific path resolution, you can just implement your own and assign it to the engine instance with:

```php
$plates = Engine::withResolveTemplatePath(new MyCustomResolveTemplatePath());
```

The resolve template path should always resolve a string that represents a verified path on the filesystem or throw a TemplateNotFound exception.
