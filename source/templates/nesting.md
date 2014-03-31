---
layout: layout
title: Nesting
---

Nesting
=======

Including another template into the current template is done using the `insert()` function:

~~~language-php
<?php $this->insert('header') ?>

<p>Your content.</p>

<?php $this->insert('footer') ?>
~~~

The `insert()` function also works with [folders](/engine/folders/): 

~~~language-php
<?php $this->insert('partials::header') ?>
~~~

## Assigning variables

As of version 2.x, nested templates are self-contained objects, meaning they do not share variables with their parent template. To assign variables to a nested template, pass them as an array:

~~~language-php
<?php $this->insert('header', array('name' => 'Jonathan')) ?>

<p>Your content.</p>

<?php $this->insert('footer') ?>
~~~

These variables will then be available within the nested template from the `$this` pseudo-variable (ie. `<?=$this->name?>`).