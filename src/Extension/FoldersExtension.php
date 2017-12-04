<?php

namespace League\Plates\Extension;

use League\Plates;

class FoldersExtension implements Plates\Extension
{
    public function register(Plates\Engine $engine) {

    }

    /** assumes the template name is in the format of `folder::path`.
        folders */
    private function folderResolveName() {
        return function($name, array $context) use ($folders, $sep) {
            if (strpos($name, $sep) === false) {
                return $name;
            }

            list($folder, $path) = explode($sep, $name);
            if (!isset($folders[$folder])) {
                return $name;
            }

            return Plates\Util\joinPath([$folders[$folder], $path]);
        };
    }

    /** Creates the folder fallback resolve name */
    private function folderFallbackResolveName() {
        return function($name, array $context, $resolve) use ($folders, $sep) {
            if (file_exists($name)) {
                return $name;
            }

            $resolve($name);
        };
    }
}
