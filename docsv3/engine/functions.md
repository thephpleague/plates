---
layout: default
permalink: engine/functions/
title: Functions
---

Functions
=========

While [extensions](/engine/extensions/) are awesome for adding additional reusable functionality to Plates, sometimes it's easier to just create a one-off function for a specific use case. Plates makes this easy to do.

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
<h1>Hello <?=$this->e($this->uppercase($name))</h1>
~~~

It can also be used in a [batch](/templates/functions/#batch-function-calls) compatible function:

~~~ php
<h1>Hello <?=$this->e($name, 'uppercase')</h1>
~~~