---
layout: default
permalink: templates/nesting/
title: Nesting
---

Nesting
=======

Including another template into the current template is done using the `insert()` function:

~~~ php
<?php $this->insert('header') ?>

<p>Your content.</p>

<?php $this->insert('footer') ?>
~~~

The `insert()` function also works with [folders](/engine/folders/):

~~~ php
<?php $this->insert('partials::header') ?>
~~~

## Alternative syntax

The `insert()` function automatically outputs the rendered template. If you prefer to manually output the response, use the `fetch()` function instead:

~~~ php
<?=$this->fetch('header')?>
~~~

## Assign data

To assign data (variables) to a nested template, pass them as an array to the `insert()` or `fetch()` functions. This data will then be available as locally scoped variables within the nested template.

~~~ php
<?php $this->insert('header', ['name' => 'Jonathan']) ?>

<p>Your content.</p>

<?php $this->insert('footer') ?>
~~~