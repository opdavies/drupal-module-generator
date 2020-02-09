<?php

namespace Opdavies\Tests\DrupalModuleGenerator\Command;

use Opdavies\DrupalModuleGenerator\Command\GenerateDrupal7Command;
use Opdavies\DrupalModuleGenerator\Exception\CannotCreateModuleException;
use Symfony\Component\Console\Tester\CommandTester;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class GenerateDrupal7ModuleCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        (new Filesystem())->remove('test_module');
    }

    /** @test */
    public function it_throws_an_exception_if_the_directory_already_exists() {
        mkdir('test_module');

        $this->expectExceptionObject(CannotCreateModuleException::directoryAlreadyExists());

        $finder = new Finder();
        $command = new GenerateDrupal7Command($finder);

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'module-name' => 'test_module'
        ]);
    }

    /** @test */
    public function it_creates_a_new_module_directory()
    {
        $finder = new Finder();
        $command = new GenerateDrupal7Command($finder);

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'module-name' => 'test_module',
        ]);

        $this->assertTrue(is_dir('test_module'));
    }

    /** @test */
    public function it_generates_an_info_file()
    {
        $finder = new Finder();
        $command = new GenerateDrupal7Command($finder);

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'module-name' => 'test_module',
        ]);

        $this->assertTrue(is_file('test_module/test_module.info'));

        $contents = file_get_contents('test_module/test_module.info');

        $this->assertStringContainsString('name = test_module', $contents);
        $this->assertStringContainsString('description = The description for test_module.', $contents);
    }
}
