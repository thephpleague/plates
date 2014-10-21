---
layout: default
permalink: templates/functions/
title: Functions
---

Functions
=========

Template functions in Plates are accessed using the `$this` pseudo-variable.

~~~ php
<p>Hello, <?=$this->escape($name)?></p>
~~~


## Custom fuctions

In addition to the functions included with Plates, it's also possible to add [one-off functions](/engine/functions/), or even groups of functions, known as [extensions](/engine/extensions/).

## Batch function calls

Sometimes you need to apply more than function to a variable in your templates. This can become somewhat illegible. The `batch()` function helps by allowing you to apply multiple functions, including native PHP functions, to a variable at one time.

~~~ php
<!-- Example without using batch -->
<p>Welcome <?=$this->escape(strtoupper(strip_tags($name)))?></p>

<!-- Example using batch -->
<p>Welcome <?=$this->batch($name, 'strip_tags|strtoupper|escape')?></p>
~~~

The [escape](/templates/escaping/) functions also support batch function calls.

~~~ php
<p>Welcome <?=$this->e($name, 'strip_tags|strtoupper')?></p>
~~~

The batch functions works well for "piped" functions that accept one parameter, modify it, and then return it. It's important to note that they execute functions left to right and will favour extension functions over native PHP functions if there are conflicts.

~~~ php
<!-- Will output: JONATHAN -->
<?=$this->batch('Jonathan', 'escape|strtolower|strtoupper')?>

<!-- Will output: jonathan -->
<?=$this->batch('Jonathan', 'escape|strtoupper|strtolower')?>
~~~