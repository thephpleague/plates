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
        $c->wrapStack('path.resolvePath', function($stack, $c) {
            $config = $c;
            return array_merge([
                'folders' => foldersResolvePath(
                    $c->get('folders.folders'),
                    $c->get('config')['folder_separator'],
                    $c->get('fileExists')
                )
            ], $stack);
        });
        $c->wrapComposed('path.normalizeName', function($composed, $c) {
            return array_merge($composed, [
                'folders.stripFolders' => stripFoldersNormalizeName($c->get('folders.folders'))
            ]);
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
