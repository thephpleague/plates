+++
title = "Sections"
linkTitle = "Templates Sections"
[menu.main]
parent = "templates"
weight = 6
+++

The `start()` and `stop` functions allow you to build sections (or blocks) of content within your template, and instead of them being rendered directly, they are saved for use elsewhere. For example, in your [layout]({{< relref "templates/layouts.md" >}}) template.

## Creating sections

You define the name of the section with the `start()` function. To end a section call the `stop()` function.

~~~ php
<?php $this->start('welcome') ?>

    <h1>Welcome!</h1>
    <p>Hello <?=$this->e($name)?></p>

<?php $this->stop() ?>
~~~

## Stacking section content

By default, when you render a section its content will overwrite any existing content for that section. However, it's possible to append/prepend (or stack) the content instead using the `push()` or `unshift()` method respectively. This can be useful for specifying any JavaScript libraries or CSS files required by your child views.

~~~ php
<?php $this->push('scripts') ?>
    <script src="example.js"></script>
<?php $this->end() ?>

<?php $this->unshift('styles') ?>
    <link rel="stylesheet" href="example.css" />
<?php $this->end() ?>
~~~

<p class="message-notice">The <code>end()</code> function is simply an alias of <code>stop()</code>. These functions can be used interchangeably.</p>

## Accessing section content

Access rendered section content using the name you assigned in the `start()` method. This variable can be accessed from the current template and layout templates using the `section()` function.

~~~ php
<?=$this->section('welcome')?>
~~~

<p class="message-notice">Prior to Plates 3.0, accessing template content was done using either the <code>content()</code> or <code>child()</code> functions. For consistency with sections, this is no longer possible.</p>

## Default section content

In situations where a page doesn't implement a particular section, it's helpful to assign default content. There are a couple ways to do this:

### Defining it inline

If the default content can be defined in a single line of code, it's best to simply pass it as the second parameter of the `section()` function.

~~~ php
<div id="sidebar">
    <?=$this->section('sidebar', $this->fetch('default-sidebar')?>
</div>
~~~

### Use an if statement

If the default content requires more than a single line of code, it's best to use a simple if statement to check if a section exists, and otherwise display the default.

~~~ php
<div id="sidebar">
    <?php if ($this->section('sidebar')): ?>
        <?=$this->section('sidebar')?>
    <?php else: ?>
        <ul>
            <li><a href="/link">Example Link</a></li>
            <li><a href="/link">Example Link</a></li>
            <li><a href="/link">Example Link</a></li>
            <li><a href="/link">Example Link</a></li>
            <li><a href="/link">Example Link</a></li>
        </ul>
    <?php endif ?>
</div>
~~~

