---
layout: default
permalink: templates/escaping/
title: Escaping
---

Escaping
========

Escaping is a form of [data filtering](http://www.phptherightway.com/#data_filtering) which sanitizes unsafe, user supplied input prior to outputting it as HTML. Plates provides two shortcuts to the `htmlspecialchars()` function.

## Escaping example

~~~ php
<h1>Hello, <?=$this->escape($name)?></h1>

<!-- Using the alternative, shorthand function -->
<h1>Hello, <?=$this->e($name)?></h1>
~~~

## Batch function calls

The escape functions also support [batch](/templates/functions/#batch-function-calls) function calls, which allow you to apply multiple functions, including native PHP functions, to a variable at one time.

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

The easiest way to stay safe is enabling autoescaping for all string values by default. You can done it by setting this as default behavior for Engine:

~~~ php
$engine->setAutoescape(true);
$engine->isAutoescape(); // true
~~~

Or directly when rendering:
~~~ php
$template->render(array('name' => '<a href="#">John</a>'), true);
$engine->render('profile', array('name' => '<a href="#">John</a>'), true);
~~~

If you rendering template with autoescaping enabled, you can get unescaped variable value inside template:
~~~ php
$this->get('name'); // get clean value
$this->get('name', 'strtoupper|strtolower') // get clean value and apply functions
~~~

(if variable does not exists NULL will be returned without passing through functions)