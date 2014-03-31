---
layout: layout
title: Variables
---

Layouts
=======

The `layout()` function allows you to define a layout template that a template will implement.

## Defining a layout

~~~.language-php
<?php $this->layout('template') ?>

<?php $this->title = 'User Profile' ?>

<h1>User Profile</h1>
<p>Hello, <?=$this->e($this->name)?></p>
~~~

This function works with [folders](/engine/folders/) as well:

~~~language-php
<?php $this->insert('shared::template') ?>
~~~

## Accessing the content

To access the rendered template content within the layout, use the `content()` function. This will return all outputted content from the template that hasn’t been defined in a [section](/templates/sections/).

~~~.language-php
<html>
<head>
    <title><?=$this->title?></title>
</head>

<body>

<?=$this->content()?>

</body>
</html>
~~~

Note, prior to version 2.x, this was done using the `child()` function, will still exists as an alias.

## Rendering order

The `layout()` function can be called anywhere in a template, since the layout is actually rendered after the template. This is helpful as you can assign variables in your template that will then be available when the layout is rendered. For example, a page title.

## Stacked layouts

Added in version 2.x was the ability to stack layouts, allowing even further simplification and organization of templates. Instead of just using one main layout, you can now break templates into more defined layouts, which themselves implement the main layout. Consider this example:

### The main site layout

~~~.language-php
<!-- template.tpl -->

<html>
<head>
    <title><?=$this->title?></title>
</head>
<body>

<?=$this->content()?>

</body>
</html>
~~~

### The blog layout

~~~.language-php
<!-- blog.tpl -->

<?php $this->layout('template') ?>

<h1>The Blog</h1>

<section>
    <article>
        <?=$this->content()?>
    </article>
    <aside>
        <?=$this->insert('blog-sidebar')?>
    </aside>
</section>
~~~

### A blog article

~~~.language-php
<!-- blog-article.tpl -->

<?php $this->title = $this->article->title ?>
<?php $this->layout('blog') ?>

<h2><?=$this->article->title?></h2>
<?=$this->article->content?>
~~~