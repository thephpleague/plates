---
layout: default
permalink: templates/layouts/
title: Layouts
---

Layouts
=======

The `layout()` function allows you to define a layout template that a template will implement. It's like having separate header and footer templates in one file.

## Define a layout

The `layout()` function can be called anywhere in a template, since the layout template is actually rendered second. Typically it's placed at the top of the file.

~~~ php
<?php $this->layout('template') ?>

<h1>User Profile</h1>
<p>Hello, <?=$this->e($name)?></p>
~~~

This function also works with [folders](/engine/folders/):

~~~ php
<?php $this->layout('shared::template') ?>
~~~

## Assign data

To assign data (variables) to a layout template, pass them as an array to the `layout()` function. This data will then be available as locally scoped variables within the layout template.

~~~ php
<?php $this->layout('template', ['title' => 'User Profile']) ?>
~~~

## Accessing the content

To access the rendered template content within the layout, use the `section()` function, passing `'content'` as the section name. This will return all outputted content from the template that hasn't been defined in a [section](/templates/sections/).

~~~ php
<html>
<head>
    <title><?=$this->e($title)?></title>
</head>
<body>

<?=$this->section('content')?>

</body>
</html>
~~~

## Stacked layouts

Plates allows stacking of layouts, allowing even further simplification and organization of templates. Instead of just using one main layout, it's possible to break templates into more specific layouts, which themselves implement a main layout. Consider this example:

### The main site layout

<div class="filename">template.php</div>
~~~ php
<html>
<head>
    <title><?=$this->e($title)?></title>
</head>
<body>

<?=$this->section('content')?>

</body>
</html>
~~~

### The blog layout

<div class="filename">blog.php</div>
~~~ php
<?php $this->layout('template') ?>

<h1>The Blog</h1>

<section>
    <article>
        <?=$this->section('content')?>
    </article>
    <aside>
        <?=$this->insert('blog/sidebar')?>
    </aside>
</section>
~~~

### A blog article

<div class="filename">blog-article.php</div>
~~~ php
<?php $this->layout('blog', ['title' => $article->title]) ?>

<h2><?=$this->e($article->title)?></h2>
<article>
    <?=$this->e($article->content)?>
</article>
~~~