<?php

namespace Templates;

use League\Plates\Template\Template;
use League\Plates\Template\TemplateClassInterface;

class Profile implements TemplateClassInterface
{

    public function __construct(
        public string $name,
    ) {}

    public function display(Template $tpl): void { ?>
<?php $tpl->layout(new Layout('User Profile')) ?>
<?php // $this->layout('layout', ['title' => 'User Profile']) // this is working too ! ?>
<?php // $this->layout(new Layout(), ['title' => 'User Profile']) // this is working too ! ?>

<h1>User Profile</h1>
<p>Hello, <?=$tpl->e($this->name)?>!</p>

<?php $tpl->insert(new Sidebar()) ?>

<?php $tpl->push('scripts') ?>
    <script>
        // Some JavaScript
    </script>
<?php $tpl->end() ?>
<?php
    }
}