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