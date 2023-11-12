<?php

namespace Templates;

use League\Plates\Template\Template;
use League\Plates\Template\TemplateClassInterface;

class Layout implements TemplateClassInterface
{

    public function __construct(
        public ?string $title = null,
        public ?string $company = null,
    ) {}

    public function display(Template $tpl): void { ?>
<html>
<head>
    <title><?=$tpl->e($this->title)?> | <?=$tpl->e($this->company)?></title>
</head>
<body>

<?=$tpl->section('content')?>

<?=$tpl->section('scripts')?>

</body>
</html>
<?php
    }
}