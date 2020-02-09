<?php

use Opdavies\DrupalModuleGenerator\Command\GenerateDrupal7Command;
use Symfony\Component\Console\Application;

require_once __DIR__.'/vendor/autoload.php';

$app = new Application();

$app->addCommands([
    new GenerateDrupal7Command(),
]);

$app->run();
