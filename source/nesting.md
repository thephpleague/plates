---
layout: layout
title: Nesting
---

Nesting
=======

Nesting (or including) another template into the current template is done using the `insert()` function:

~~~language-php
<?php $this->insert('header') ?>

<p>Your content.</p>

<?php $this->insert('footer') ?>
~~~

The `insert()` function also works with folders: 

~~~language-php
<?php $this->insert('partials::header') ?>
~~~

## Assigning variables

You can also assign [variables](variables) as an array when nesting templates. Be aware that these variables will be available to the entire template object, not just the inserted template.

~~~language-php
<?php $this->insert('header', array('name' => 'Jonathan')) ?>

<p>Your content.</p>

<?php $this->insert('footer') ?>
~~~