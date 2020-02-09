<?php

use Opdavies\DrupalModuleGenerator\Command\GenerateDrupal7Command;
use Opdavies\DrupalModuleGenerator\Service\TestNameConverter;
use Symfony\Component\Console\Application;
use Symfony\Component\Finder\Finder;

require_once __DIR__.'/vendor/autoload.php';

$app = new Application();

$finder = new Finder();
$testNameConverter = new TestNameConverter();

$app->addCommands([
    new GenerateDrupal7Command($finder, $testNameConverter),
]);

$app->run();
