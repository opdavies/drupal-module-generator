<?php

namespace Opdavies\DrupalModuleGenerator\Exception;

final class CannotCreateModuleException extends \RuntimeException
{
    public static function directoryAlreadyExists()
    {
        return new static('The given directory name for the module already exists.');
    }
}
