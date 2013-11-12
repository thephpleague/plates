Escape extension
================

Escaping is a form of [data filtering](http://www.phptherightway.com/#data_filtering) which sanitizes unsafe, user supplied input prior to outputting it as HTML. Plates provides two shortcuts to the `htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')` function.

The escape extension comes packaged with Plates and is enabled by default.

## Escape example

~~~language-php
<?=$this->escape($this->var)?>
<?=$this->e($this->var)?>
~~~

## Warning about escaping variables

Probably the biggest issue with native PHP templates is the inability to auto-escape variables properly. Template languages like Twig and Smarty can identify "echoed" variables during a parsing stage and then auto-escape them. This cannot be done in native PHP as the language does not offer overloading functionality for it's output functions (ie. `print` and `echo`).

Don't worry, escaping can still be done safely, it just means you are responsible for manually escaping each variable on output. Consider creating a snippet for one of the above, built-in escaping functions to make this process easier.