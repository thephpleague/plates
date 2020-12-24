<?php

namespace Templates;

final class TemplateContext
{
    private $navItems;

    public function navItems(): array {
        return $this->navItems;
    }

    /** @param \NavItem[] $navItems */
    public function withNavItems(array $navItems): self {
        $self = clone $this;
        $self->navItems = $navItems;
        return $self;
    }
}
