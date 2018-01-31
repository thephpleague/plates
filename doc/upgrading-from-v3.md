---
layout: default
permalink: upgrading-from-v3/
title: Upgrading from V3
---

Upgrading from V3
=================

Version 4 of plates has been completely redesigned from the ground up. There are some significant differences that will need to be addressed if you are familiar with v3.

## Creating Engines

The default constructor now longer accepts the templates path and extension. This has been moved over to the `create` static constructor. Also, the default extension for templates is `phtml` instead of `php`, so if you migrating an existing codebase, you'll need to explicitly set the extension.

```diff
- new League\Plates\Engine('/path/to/templates')
+ League\Plates\Engine::create('/path/to/templates', 'php')
```

## Render Context

Templates no longer rendered within the context of the `Template`. This has been replaced by the `RenderContext` which is just an object injected into each template. The default name for the render context is `$v`, but this is configurable.

So in your templates, you'll want to change:

```diff
- <?=$this->section()?>
+ <?=$v->section()?>
```

The `RenderContext` is more powerful and flexible than the original Template class because it's completely configurable.

For BC reasons, we are currently binding `$this` to the render context which means `$this === $v` when inside of your templates.

## Templates

The `Template` class is completely different and has been moved to `Leauge\Plates\Template`. In v3, the Template class was responsible for rendering the php files and providing context within the templates themselves. This behavior has been moved into the `RenderTemplate` implementations. Templates are now simply immutable value objects.

So this means you can no longer manually create and render templates, you need a `RenderTemplate` to actuall render the template into a string.

```php
$renderTemplate = League\Plates\Engine::create('/path/to/templates')
    ->getContainer()
    ->get('renderTemplate');

echo $renderTemplate->renderTemplate(
    new League\Plates\Template('profile', ['name' => 'Jonathan'])
);
```

However, this is exactly what the Engine render method does, so you might as well just refactor to use the Engine's render method.

## Extension Interface

The new interface is located here: `League\Plates\Extension`. The contents of the interface are exactly the same. We've kept the `League\Plates\Extension\ExtensionInterface` around as an alias to the former, but it is deprecated and will be removed in v4.1.

## Accessing the engine and template in Extensions

This paradigm no longer exists with v4 due to how template functions work now. Functions in v4 now accept the `League\Plates\Extension\RenderContext\FuncArgs` as the only argument which has access to the RenderTemplate and curren template instance. Since v4 also utilizes an IoC container, any dependencies needed can be injected into the function constructor. So instead of a func having access to the entire engine, they can just receive the deps they need.

## BC Extension

To do - reference the [BCExtension](https://github.com/thephpleague/plates/issues/214) once it gets built.

