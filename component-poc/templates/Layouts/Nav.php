<?php

namespace Templates\Layouts;

use Templates\TemplateContext;

final class Nav
{
    private $navItems;

    /** @param \NavItem[] $navItems */
    public function __construct(array $navItems) {
        $this->navItems = $navItems;
    }

    public function __invoke(): void {
    ?>  <nav>
            <ul>
                <?php foreach ($this->navItems as $navItem): ?>
                <li>
                    <?php if ($navItem->link()): ?>
                        <a href="<?=$navItem->link()?>"><?=$navItem->title()?></a>
                    <?php else: ?>
                        <?=$navItem->title()?>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </nav> <?php
    }

    public static function fromContext() {
        return function(TemplateContext $context) {
            return (new self($context->navItems()))();
        };
    }
}
