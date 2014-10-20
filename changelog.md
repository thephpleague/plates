---
layout: default
permalink: changelog/
title: Changelog
---

Changelog
=========

All notable changes to this project will be documented in this file.

## Version 3.0.3 - 2014-10-20

### Added

- Added ability to define the default content of a section. See [sections](/templates/sections/#default-section-content) for more information.

## Version 3.0.2 - 2014-10-01

### Changed

- Fixed bug with fallback folders, where the file extension wasn't being applied.

## Version 3.0.0 - 2014-09-27

### Added

- Added ability to share data across templates.
- Added ability to preassign data to specific templates.
- Added ability to create one-off template [functions](/engine/functions/), without using an extension.
- Added new folder "fall backs", where missing folder templates will fall back to the default folder.
- Added new `render()` method to `Engine` class, improving the use of the `Engine` as the primary API.

### Changed

- Templates variables are now accessed without the `$this` pseudo-variable.
- Total overhaul to how extensions are registered. Replaced `getFunctions()` method with new `register()` method. See [extensions](/engine/extensions/) for more information.
- Section content is no longer assigned to template variables. Use the the `section()` function instead.
- Renamed section `end()` function to `stop()`. This fits more appropriately with the `start()` function.
- Renamed `get()` function to `fetch()`.
- Renamed `pathExists()` method in the `Engine` class to `exists()`.
- Renamed `getTemplatePath()` method in the `Engine` class to `path()`.
- Renamed `makeTemplate()` method in the `Engine` class to `make()`.

### Removed

- Removed the ability to assign template data directly to the `Template` class. For example:  `$this->name = 'Jonathan'`. This applies both within and outside of templates. Use the `data()` method instead. See [data](http://platesphp.com/templates/data/) for more information.
- Removed `getEngine()` method from the `Template` class. There's no reason to need this anymore.
- Removed `addFolders()` method from the `Engine()` class.
- Removed `unloadExtension()` and `unloadExtensionFunction()` methods from the `Engine()` class.

## Version 2.0.0 - 2014-03-31

### Added

- Added stacked layouts, allowing even further simplification and organization of templates.
- Added new `unloadExtension()` and `unloadExtensionFunction()` methods to the `Engine()` class.
- Added `getEngine()` method to the `Template` class.
- Added `addFolders()` and `loadExtensions()` methods to the `Engine()` class.

### Changed

- Nested templates are now self-contained objects, with their own variables and layouts.