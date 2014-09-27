---
layout: default
permalink: changelog/
title: Changelog
---

Changelog
=========

All notable changes to this project will be documented in this file.

## Version 3.0

### Added

- Added ability to share data across templates.
- Added ability to preassign data to specific templates.
- Added ability to create one-off template functions (without using an extension).
- Added new folder theme mode (where missing folder templates will fall back to the default folder).
- Added new [optional compiler](/engine/compiler/) adds automatic escaping and a cleaner template syntax.
- Added new `render()` method to `Engine` object.

### Changed

- Templates variables are now accessed without the `$this` pseudo-variable.
- Total overhaul to how extensions are registered.
- Renamed section `end()` function to `stop()`.
- Section content is no longer assigned to template variables, rather it's available via the `section()` function.
- The `get()` function has been renamed to `fetch()`.

### Removed

- Removed `getEngine()` method from template object.
- Removed `makeTemplate()` method from `Engine` object. Use the `make()` method instead.
- Removed `pathExists()` method from `Engine` object. Use the `exists()` method instead.