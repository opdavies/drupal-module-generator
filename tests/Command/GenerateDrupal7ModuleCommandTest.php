<?php

namespace Opdavies\Tests\DrupalModuleGenerator\Command;

use Opdavies\DrupalModuleGenerator\Command\GenerateDrupal7Command;
use Opdavies\DrupalModuleGenerator\Exception\CannotCreateModuleException;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;

class GenerateDrupal7ModuleCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        rmdir('test_module');
    }

    /** @test */
    public function it_throws_an_exception_if_the_directory_already_exists() {
        mkdir('test_module');

        $this->expectExceptionObject(CannotCreateModuleException::directoryAlreadyExists());

        $commandTester = new CommandTester(new GenerateDrupal7Command());
        $commandTester->execute([
            'module-name' => 'test_module'
        ]);
    }

    /** @test */
    public function it_creates_a_new_module_directory()
    {
        $commandTester = new CommandTester(new GenerateDrupal7Command());
        $commandTester->execute([
            'module-name' => 'test_module',
        ]);

        $this->assertTrue(is_dir('test_module'));
    }
        ]);

        $this->assertTrue(is_dir('my-new-drupal-module'));

        rmdir('my-new-drupal-module');
    }
}
