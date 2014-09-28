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
- Added ability to create one-off template [functions](/engine/functions/) (without using an extension).
- Added new folder fallbacks (where missing folder templates will fall back to the default folder).
- Added new `render()` method to `Engine` object.

### Changed

- Templates variables are now accessed without the `$this` pseudo-variable.
- Total overhaul to how extensions are registered. Replaced `getFunctions()` method with new `register()` method. See [extensions](/engine/extensions/) for more information.
- Renamed section `end()` function to `stop()`.
- Section content is no longer assigned to template variables. Use the the `section()` function intead.
- The `get()` function has been renamed to `fetch()`.

### Removed

- Removed the ability to assign template data directly to the template object. Use the `data()` method instead. See [data](http://platesphp.com/templates/data/) for more information.
- Removed `getEngine()` method from template object.
- Removed `makeTemplate()` method from `Engine` object. Use the `make()` method instead.
- Removed `pathExists()` method from `Engine` object. Use the `exists()` method instead.
- Removed `getTemplatePath()` method from `Engine` object. Use the `path()` method instead.