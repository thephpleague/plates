+++
title = "Nesting"
linkTitle = "Templates Nesting"
[menu.main]
parent = "templates"
weight = 4
+++

Including another template into the current template is done using the `insert()` function:

~~~ php
<?php $this->insert('partials/header') ?>

<p>Your content.</p>

<?php $this->insert('partials/footer') ?>
~~~

The `insert()` function also works with [folders]({{< relref "engine/folders.md" >}}):

~~~ php
<?php $this->insert('partials::header') ?>
~~~

## Alternative syntax

The `insert()` function automatically outputs the rendered template. If you prefer to manually output the response, use the `fetch()` function instead:

~~~ php
<?=$this->fetch('partials/header')?>
~~~

## Assign data

To assign data (variables) to a nested template, pass them as an array to the `insert()` or `fetch()` functions. This data will then be available as locally scoped variables within the nested template.

~~~ php
<?php $this->insert('partials/header', ['name' => 'Jonathan']) ?>

<p>Your content.</p>

<?php $this->insert('partials/footer') ?>
~~~
