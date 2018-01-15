<?php

namespace League\Plates\Extension\AutoEscape;

use League\Plates;

final class AutoEscapeExtension implements Plates\Extension
{
    public function register(Plates\Engine $plates) {
        $plates->addConfig([
            'auto_escape' => false,
        ]);
        $plates->pushComposers(function($c) {
            return $c->get('config')['auto_escape'] ? [
                'autoEscape.autoEscape' => autoEscapeComposer(),
            ] : [];
        });
    }
}
