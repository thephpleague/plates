<?php

namespace Templates;

use League\Plates\Template\Template;
use League\Plates\Template\TemplateClassInterface;

class Sidebar implements TemplateClassInterface
{

    public function display(): void { ?>
<ul>
    <li><a href="#link">Example sidebar link</a></li>
    <li><a href="#link">Example sidebar link</a></li>
    <li><a href="#link">Example sidebar link</a></li>
    <li><a href="#link">Example sidebar link</a></li>
</ul>
<?php
    }
}