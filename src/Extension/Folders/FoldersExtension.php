<?php

namespace League\Plates\Extension\Folders;

use League\Plates;

final class FoldersExtension implements Plates\Extension
{
    public function register(Plates\Engine $plates) {
        $c = $plates->getContainer();
        $c->add('folders.folders', []);
        $c->wrapStack('path.resolvePath', function($stack, $c) {
            $config = $c;
            return array_merge($stack, [
                'folders' => foldersResolvePath(
                    $c->get('folders.folders'),
                    $c->get('config')['folder_separator'],
                    $c->get('fileExists')
                )
            ]);
        });
        $c->wrapComposed('path.normalizeName', function($composed, $c) {
            return array_merge($composed, [
                'folders.stripFolders' => stripFoldersNormalizeName($c->get('folders.folders'))
            ]);
        });

        $plates->defineConfig([
            'folder_separator' => '::',
        ]);
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
