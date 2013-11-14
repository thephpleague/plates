Sections
========

The `start()` and `end()` functions allow you to build sections (or blocks) of content within your template, but instead of them being rendered directly, they are placed into variables for use elsewhere (ie. in your layout). You define the name of this variable in the `start('variable_name')` function.

## Creating sections

In the following example, the content between the `start()` and `end()` functions will be rendered into a variable called `$this->content`.

~~~language-php
<?php $this->start('content') ?>

    <h1>Welcome!</h1>
    <p>Hello <?=$this->e($this->name)?></p>

<?php $this->end() ?>
~~~

## Accessing rendered section content

Access rendered section content using the variable name you assigned in the `start()` method. This variable can be accessed from the current template, nested templates and layout templates.

~~~language-php
<?=$this->content?>
~~~