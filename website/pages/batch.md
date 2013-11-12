Batch extension
===============

Sometimes you need to apply more than function to a variable in your templates. This can become somewhat illegible. The batch extension helps by allowing you to apply multiple extension functions AND native PHP functions to a variable at one time.

The batch extension comes packaged with Plates and is enabled by default.

## Batch example

Example without using batch:

~~~language-php
<p>Welcome, <?=strtoupper($this->escape(strip_tags($this->name)))?></p>
~~~

Example using batch:

~~~language-php
<p>Welcome, <?=$this->batch($this->name, 'strip_tags|e|strtoupper')?></p>
~~~

## How the batch extension works

The batch extension works well for "pipe" functions that accept one parameter, modify it, and then return it. It's important to note that it executes functions left to right. It will also favour extension functions over native PHP functions if there are conflicts.

~~~language-php
<!-- Will output: JONATHAN -->
<?=$this->batch('Jonathan', 'e|strtolower|strtoupper')?>

<!-- Will output: jonathan -->
<?=$this->batch('Jonathan', 'e|strtoupper|strtolower')?>
~~~