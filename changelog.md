---
layout: default
permalink: changelog/
title: Changelog
---

Changelog
=========

All notable changes to this project will be documented in this file.

## Version 3.0 - 2014-09-27

### Added

- Added ability to share data across templates.
- Added ability to preassign data to specific templates.
- Added ability to create one-off template [functions](/engine/functions/), without using an extension.
- Added new folder fallbacks, where missing folder templates will fallback to the default folder.
- Added new `render()` method to `Engine` object, improving the use of the engine as the primary API.

### Changed

- Templates variables are now accessed without the `$this` pseudo-variable.
- Total overhaul to how extensions are registered. Replaced `getFunctions()` method with new `register()` method. See [extensions](/engine/extensions/) for more information.
- Renamed section `end()` function to `stop()`. This fits more appropriately with the `start()` function.
- Renamed `get()` function to `fetch()`.
- Renamed `pathExists()` method in the `Engine` object to `exists()`.
- Renamed `getTemplatePath()` method in the `Engine` object to `path()`.
- Renamed `makeTemplate()` method in the `Engine` object to `make()`.
- Section content is no longer assigned to template variables. Use the the `section()` function instead.

### Removed

- Removed the ability to assign template data directly to the template object. For example:  `$this->name = 'Jonathan'`. This applies both within and outside of templates. Use the `data()` method instead. See [data](http://platesphp.com/templates/data/) for more information.
- Removed `getEngine()` method from template object. There's no real reason to need this.