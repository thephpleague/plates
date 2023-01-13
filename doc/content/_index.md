+++
title = "Introduction"
[menu.main]
parent = "getting-started"
weight = 1
+++

[![Maintainer](http://img.shields.io/badge/maintainer-@ragboyjr-blue.svg?style=flat-square)](https://twitter.com/reinink)
[![Source Code](http://img.shields.io/badge/source-league/plates-blue.svg?style=flat-square)](https://github.com/thephpleague/plates)
[![Latest Version](https://img.shields.io/github/release/thephpleague/plates.svg?style=flat-square)](https://github.com/thephpleague/plates/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/thephpleague/plates/blob/master/LICENSE)
{{<html>}}<br/>{{</html>}}
[![Build Status](https://img.shields.io/github/workflow/status/thephpleague/plates/PHP/v3?style=flat-square)](https://github.com/thephpleague/plates/actions?query=workflow%3APHP+branch%3Av3)
[![Quality Score](https://img.shields.io/scrutinizer/g/thephpleague/plates.svg?style=flat-square)](https://scrutinizer-ci.com/g/thephpleague/plates)
[![Total Downloads](https://img.shields.io/packagist/dt/league/plates.svg?style=flat-square)](https://packagist.org/packages/league/plates)

## About

Plates is a native PHP template system that's fast, easy to use and easy to extend. It's inspired by the excellent [Twig](http://twig.sensiolabs.org/) template engine and strives to bring modern template language functionality to native PHP templates. Plates is designed for developers who prefer to use native PHP templates over compiled template languages, such as Twig or Smarty.

## Highlights

- Native PHP templates, no new [syntax]({{< relref "templates/syntax.md" >}}) to learn
- Plates is a template system, not a template language
- Plates encourages the use of existing PHP functions
- Increase code reuse with template [layouts]({{< relref "templates/layouts.md" >}}) and [inheritance]({{< relref "templates/inheritance.md" >}})
- Template [folders]({{< relref "engine/folders.md" >}}) for grouping templates into namespaces
- [Data]({{< relref "templates/data.md#preassigned-and-shared-data" >}}) sharing across templates
- Preassign [data]({{< relref "templates/data#preassigned-and-shared-data" >}}) to specific templates
- Built-in [escaping]({{< relref "templates/escaping.md" >}}) helpers
- Easy to extend using [functions]({{< relref "engine/functions.md" >}}) and [extensions]({{< relref "engine/extensions.md" >}})
- Framework-agnostic, will work with any project
- Decoupled design makes templates easy to test
- Composer ready and PSR-2 compliant

## Questions?

Plates is maintained by [RJ Garcia](https://twitter.com/ragboyjr) and originally created by [Jonathan Reinink](https://twitter.com/reinink). Submit issues to [Github](https://github.com/thephpleague/plates/issues).
