<?php

namespace Opdavies\DrupalModuleGenerator\Service;

final class ModuleNameConverter
{
    public function __invoke(string $moduleName)
    {
        $parts = explode('_', $moduleName);

        $parts = array_map(function ($part) {
            return ucfirst($part);
        }, $parts);

        return implode(' ', $parts);
    }
}
