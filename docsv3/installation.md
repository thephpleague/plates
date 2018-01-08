---
layout: default
permalink: installation/
title: Installation
---

Installation
============

## Using Composer

Plates is available on [Packagist](https://packagist.org/packages/league/plates) and can be installed using [Composer](https://getcomposer.org/). This can be done by running the following command or by updating your `composer.json` file.

~~~ bash
composer require league/plates
~~~

<div class="filename">composer.json</div>
~~~ javascript
{
    "require": {
        "league/plates": "3.*"
    }
}
~~~

Be sure to also include your Composer autoload file in your project:

~~~ php
require 'vendor/autoload.php';
~~~

## Downloading .zip file

This project is also available for download as a `.zip` file on GitHub. Visit the [releases page](https://github.com/thephpleague/plates/releases), select the version you want, and click the "Source code (zip)" download button.