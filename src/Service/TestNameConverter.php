<?php

namespace Opdavies\DrupalModuleGenerator\Service;

final class TestNameConverter
{
    public function __invoke(string $moduleName)
    {
        $parts = explode('_', $moduleName);

        $parts = array_map(function ($part) {
            return ucfirst($part);
        }, $parts);

        return implode('', $parts).'Test';
    }
}
