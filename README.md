Plates
======

**NOTE: V4 docs are currently a work in progress. Contributions are welcome.**

[![Maintainer](http://img.shields.io/badge/maintainer-@ragboyjr-blue.svg?style=flat-square)](https://twitter.com/ragboyjr)
[![Source Code](http://img.shields.io/badge/source-league/plates-blue.svg?style=flat-square)](https://github.com/thephpleague/plates)
[![Latest Version](https://img.shields.io/github/release/thephpleague/plates.svg?style=flat-square)](https://github.com/thephpleague/plates/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/thephpleague/plates/master.svg?style=flat-square)](https://travis-ci.org/thephpleague/plates)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/thephpleague/plates.svg?style=flat-square)](https://scrutinizer-ci.com/g/thephpleague/plates/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/thephpleague/plates.svg?style=flat-square)](https://scrutinizer-ci.com/g/thephpleague/plates)
[![Total Downloads](https://img.shields.io/packagist/dt/league/plates.svg?style=flat-square)](https://packagist.org/packages/league/plates)

Plates is a native PHP template system that's fast, easy to use and easy to extend. It's inspired by the excellent [Twig](http://twig.sensiolabs.org/) template engine and strives to bring modern template language functionality to native PHP templates. Plates is designed for developers who prefer to use native PHP templates over compiled template languages, such as Twig or Blade.

### Highlights

- Native PHP templates, no new [syntax](http://platesphp.com/templates/syntax/) to learn
- Plates is a template system, not a template language
- Plates encourages the use of existing PHP functions and conventions
- Increase code reuse with template [layouts](http://platesphp.com/templates/layouts/) and [inheritance](http://platesphp.com/templates/inheritance/)
- Template [folders](http://platesphp.com/engine/folders/) for grouping templates into namespaces
- [Data](http://platesphp.com/templates/data/#preassigned-and-shared-data) sharing across templates
- Preassign [data](http://platesphp.com/templates/data/#preassigned-and-shared-data) to specific templates
- Built-in [escaping](http://platesphp.com/templates/escaping/) helpers
- Simple design crafted for extendability - most features are built as extensions
- Everything is customizable, don't like the behavior of something, you can change it
- Composable naming strategies allowing relative templates, folders, and dynamic base paths.
- Framework-agnostic, will work with any project
- Decoupled design makes templates easy to test
- Supports non-php file rendering for img or svg files to include in your templates.
- Composer ready and PSR-2 compliant

## Installation

Plates is available via Composer:

```bash
composer require league/plates:v4.0.0-alpha
```

## Documentation

Full documentation can be found at [platesphp.com](http://platesphp.com/).

## Testing

```bash
make test
```

## Contributing

Please see [CONTRIBUTING](https://github.com/thephpleague/plates/blob/master/CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email rj@bighead.net instead of using the issue tracker.

## Credits

- [RJ Garcia](https://github.com/ragboyjr) (Current Maintainer)
- [Jonathan Reinink](https://github.com/reinink) (Original Author)
- [All Contributors](https://github.com/thephpleague/plates/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/thephpleague/plates/blob/master/LICENSE) for more information.
