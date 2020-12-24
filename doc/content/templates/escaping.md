+++
title = "Escaping"
linkTitle = "Templates Escaping"
[menu.main]
parent = "templates"
weight = 8
+++

Escaping is a form of [data filtering](http://www.phptherightway.com/#data_filtering) which sanitizes unsafe, user supplied input prior to outputting it as HTML. Plates provides two shortcuts to the `htmlspecialchars()` function.

## Escaping example

~~~ php
<h1>Hello, <?=$this->escape($name)?></h1>

<!-- Using the alternative, shorthand function -->
<h1>Hello, <?=$this->e($name)?></h1>
~~~

## Batch function calls

The escape functions also support [batch]({{< relref "templates/functions.md#batch-function-calls" >}}) function calls, which allow you to apply multiple functions, including native PHP functions, to a variable at one time.

~~~ php
<p>Welcome <?=$this->e($name, 'strip_tags|strtoupper')?></p>
~~~

## Escaping HTML attributes

<p class="message-notice">It's VERY important to always double quote HTML attributes that contain escaped variables, otherwise your template will still be open to injection attacks.</p>

Some [libraries](http://framework.zend.com/manual/2.1/en/modules/zend.escaper.escaping-html-attributes.html) go as far as having a special function for escaping HTML attributes. However, this is somewhat redundant considering that if a developer forgets to properly quote an HTML attribute, they will likely also forget to use this special function. Here is how you properly escape HTML attributes:

~~~ php
<!-- Good -->
<img src="portrait.jpg" alt="<?=$this->e($name)?>">

<!-- BAD -->
<img src="portrait.jpg" alt='<?=$this->e($name)?>'>

<!-- BAD -->
<img src="portrait.jpg" alt=<?=$this->e($name)?>>
~~~

## Automatic escaping

Probably the biggest drawbacks to native PHP templates is the inability to auto-escape variables properly. Template languages like Twig and Smarty can identify "echoed" variables during a parsing stage and automatically escape them. This cannot be done in native PHP as the language does not offer overloading functionality for it's output functions (ie. `print` and `echo`).

Don't worry, escaping can still be done safely, it just means you are responsible for manually escaping each variable on output. Consider creating a snippet for one of the above, built-in escaping functions to make this process easier.
