---
layout: default
permalink: templates/sections/
title: Sections
---

Sections
========

The `start()` and `stop()` functions allow you to build sections (or blocks) of content within your template, and instead of them being rendered directly, they are saved for use elsewhere. For example, in your [layout](/templates/layouts/) template.

## Creating sections

You define the name of the section in the `start()` function, and end the section with the `stop()` function.

~~~ php
<?php $this->start('welcome') ?>

    <h1>Welcome!</h1>
    <p>Hello <?=$name?></p>

<?php $this->stop() ?>
~~~

## Accessing section content

Access rendered section content using the name you assigned in the `start()` method. This variable can be accessed from the current template and layout templates using the `section()` function.

~~~ php
<?=$this->section('welcome')?>
~~~

<p class="message-notice">Prior to Plates 3.0, accessing template content was done using either the <code>content()</code> or <code>child()</code> functions. For consistency with sections, this is no longer possible.</p>