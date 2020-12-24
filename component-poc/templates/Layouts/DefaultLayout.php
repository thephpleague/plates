<?php

namespace Templates\Layouts;

use function League\Plates\p;

final class DefaultLayout
{
    private $body;
    private $title;
    private $css;

    private function __construct() {}
    public static function new(callable $body, string $title = 'Test App') {
        $self = new self();
        $self->body = $body;
        $self->title = $title;
        return $self;
    }

    public function withTitle(string $title): self {
        $self = clone $this;
        $self->title = $title;
        return $self;
    }

    public function withCss(callable $css): self {
        $self = clone $this;
        $self->css = $css;
        return $self;
    }

    public function __invoke() { ?>
    <!DOCTYPE html>
    <html lang="en">
      <head>
        <title><?=$this->title?></title>
        <?=p($this->css)?>
      </head>
      <body>
        <?=p(Nav::fromContext())?>
        <div class="container mx-auto">
          <?=p($this->body)?>
        </div>
      </body>
    </html>
    <?php }
}
