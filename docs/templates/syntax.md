---
layout: default
permalink: templates/syntax/
title: Syntax
---

Syntax
======

While the actual syntax you use in your templates is entirely your choice (it's just PHP after all), we suggest the following syntax guidelines to help keep templates clean and legible.

## Guidelines

- Always use HTML with inline PHP. Never use blocks of PHP.
- Always escape potentially dangerous variables prior to outputting using the built-in escape functions. More on escaping [here](/templates/escaping/).
- Always use the short echo syntax (`<?=`) when outputting variables. For all other inline PHP code, use full the `<?php` tag. Do not use [short tags](http://us3.php.net/manual/en/ini.core.php#ini.short-open-tag).
- Always use the [alternative syntax for control structures](http://php.net/manual/en/control-structures.alternative-syntax.php), which are designed to make templates more legible.
- Never use PHP curly brackets.
- Only ever have one statement in each PHP tag.
- Avoid using semicolons. They are not needed when there is only one statement per PHP tag.
- Never use the `use` operator. Templates should not be interacting with classes in this way.
- Never use the `for`, `while` or `switch` control structures. Instead use `if` and `foreach`.
- Avoid variable assignment.

## Syntax example

Here is an example of a template that complies with the above syntax rules.

~~~ php
<?php $this->layout('template', ['title' => 'User Profile']) ?>

<h1>Welcome!</h1>
<p>Hello <?=$this->e($name)?></p>

<h2>Friends</h2>
<ul>
    <?php foreach($friends as $friend): ?>
        <li>
            <a href="/profile/<?=$this->e($friend->id)?>">
                <?=$this->e($friend->name)?>
            </a>
        </li>
    <?php endforeach ?>
</ul>

<?php if ($invitations): ?>
    <h2>Invitations</h2>
    <p>You have some friend invites!</p>
<?php endif ?>
~~~
