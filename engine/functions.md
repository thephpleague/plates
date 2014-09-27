---
layout: default
permalink: engine/functions/
title: Functions
---

Functions
=========

While [extensions](/engine/extensions/) are awesome for adding additional functionality to Plates, sometimes it's easier to just create a one-off function for a specific use case. Plates makes this easy to do.

## Registering functions

~~~ php
// Create new Plates engine
$templates = new \League\Plates\Engine('/path/to/templates');

// Register a one-off function
$templates->registerFunction('uppercase', function ($string) {
    return strtoupper($string);
});
~~~

To use this function in a template, simply call it like any other function:

~~~ php
<h1>Hello <?=$this->uppercase($name)</h1>
~~~