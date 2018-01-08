<?php

namespace League\Plates\Extension\Folders;

use League\Plates;

final class FoldersExtension implements Plates\Extension
{
    public function register(Plates\Engine $plates) {
        $c = $plates->getContainer();
        $c->merge('config', [
            'folder_separator' => '::',
        ]);
        $c->add('folders.folders', []);
        $c->wrap('path.resolvePath.stack', function($stack, $c) {
            $config = $c;
            return array_merge([
                'folders' => foldersResolvePath(
                    $c->get('folders.folders'),
                    $c->get('config')['folder_separator'],
                    $c->get('fileExists')
                )
            ], $stack);
        });
        $plates->addMethods([
            'addFolder' => function($plates, $folder, $prefixes, $fallback = false) {
                $prefixes = is_string($prefixes) ? [$prefixes] : $prefixes;
                if ($fallback) {
                    $prefixes[] = '';
                }
                $plates->getContainer()->merge('folders.folders', [
                    $folder => [
                        'folder' => $folder,
                        'prefixes' => $prefixes,
                    ]
                ]);
            },
        ]);
    }
}
