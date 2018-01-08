<?php

namespace League\Plates\Extension\NormalizeName;

use League\Plates;

/** simply converts a path to a name in case it's not normalized. This is needed because template names can be paths, relative paths, or anything in between, this extension
    ensures that there is a normalized name always which allows for matching off of the name. */
final class NormalizeNameExtension implements Plates\Extension
{
    public function register(Plates\Engine $plates) {

    }
}
