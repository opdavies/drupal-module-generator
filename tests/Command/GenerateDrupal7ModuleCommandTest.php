<?php

namespace Opdavies\Tests\DrupalModuleGenerator\Command;

use Opdavies\DrupalModuleGenerator\Command\GenerateDrupal7Command;
use Opdavies\DrupalModuleGenerator\Exception\CannotCreateModuleException;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class GenerateDrupal7ModuleCommandTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_if_the_directory_already_exists() {
        mkdir('my-existing-drupal-module');

        try {
            $commandTester = new CommandTester(new GenerateDrupal7Command());
            $commandTester->execute([
                'module-name' => 'my-existing-drupal-module',
            ]);
        } catch (CannotCreateModuleException $e) {
            $this->addToAssertionCount(1);
        }

        rmdir('my-existing-drupal-module');
    }

    /** @test */
    public function it_creates_a_new_module_directory()
    {
        $commandTester = new CommandTester(new GenerateDrupal7Command());
        $commandTester->execute([
            'module-name' => 'my-new-drupal-module',
        ]);

        $this->assertTrue(is_dir('my-new-drupal-module'));

        rmdir('my-new-drupal-module');
    }
}
