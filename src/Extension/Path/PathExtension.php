<?php

namespace League\Plates\Extension\Path;

use League\Plates;

final class PathExtension implements Plates\Extension
{
    public function register(Plates\Engine $plates) {
        $c = $plates->getContainer();
        $c->add('path.resolvePath.stack', function($c) {
            $config = $c->get('config');

            // wrap base dir in an array if not already
            $base_dir = isset($config['base_dir']) ? $config['base_dir'] : null;
            $base_dir = $base_dir ? (is_string($base_dir) ? [$base_dir] : $base_dir) : $base_dir;

            return array_filter([
                'relative' =>relativeResolvePath(),
                'ext' => isset($config['ext']) ? extResolvePath($config['ext']) : null,
                'prefix' => $base_dir ? prefixResolvePath($base_dir, $c->get('fileExists')) : null,
                'id' => idResolvePath(),
            ]);
        });
        $c->add('path.resolvePath', function($c) {
            return Plates\Util\stack($c->get('path.resolvePath.stack'));
        });
        $c->wrap('compose', function($compose, $c) {
            return Plates\Util\compose(
                $compose,
                resolvePathCompose($c->get('path.resolvePath'))
            );
        });
    }
}
