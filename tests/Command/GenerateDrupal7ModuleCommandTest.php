<?php

namespace Opdavies\Tests\DrupalModuleGenerator\Command;

use Opdavies\DrupalModuleGenerator\Command\GenerateDrupal7Command;
use Opdavies\DrupalModuleGenerator\Exception\CannotCreateModuleException;
use Opdavies\DrupalModuleGenerator\Service\ModuleNameConverter;
use Opdavies\DrupalModuleGenerator\Service\TestNameConverter;
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
        $moduleNameConverter = new ModuleNameConverter();
        $testNameConverter = new TestNameConverter();
        $command = new GenerateDrupal7Command($finder, $moduleNameConverter, $testNameConverter);

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'module-name' => 'test_module'
        ]);
    }

    /** @test */
    public function it_creates_a_new_module_directory()
    {
        $finder = new Finder();
        $moduleNameConverter = new ModuleNameConverter();
        $testNameConverter = new TestNameConverter();
        $command = new GenerateDrupal7Command($finder, $moduleNameConverter, $testNameConverter);

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
        $moduleNameConverter = new ModuleNameConverter();
        $testNameConverter = new TestNameConverter();
        $command = new GenerateDrupal7Command($finder, $moduleNameConverter, $testNameConverter);

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'module-name' => 'test_module',
        ]);

        $this->assertTrue(is_file('test_module/test_module.info'));

        $contents = file_get_contents('test_module/test_module.info');

        $this->assertStringContainsString('name = Test Module', $contents);
        $this->assertStringContainsString('description = The description for Test Module.', $contents);
    }

    /** @test */
    public function it_generates_a_module_file()
    {
        $finder = new Finder();
        $moduleNameConverter = new ModuleNameConverter();
        $testNameConverter = new TestNameConverter();
        $command = new GenerateDrupal7Command($finder, $moduleNameConverter, $testNameConverter);

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'module-name' => 'test_module',
        ]);

        $this->assertTrue(is_file('test_module/test_module.module'));

        $contents = file_get_contents('test_module/test_module.module');

        $this->assertStringContainsString('The main module file for Test Module.', $contents);
    }

    /** @test */
    public function it_generates_a_test_case()
    {
        $finder = new Finder();
        $moduleNameConverter = new ModuleNameConverter();
        $testNameConverter = new TestNameConverter();
        $command = new GenerateDrupal7Command($finder, $moduleNameConverter, $testNameConverter);

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'module-name' => 'test_module',
        ]);

        $this->assertTrue(is_file('test_module/src/Tests/Functional/TestModuleTest.php'));

        $contents = file_get_contents('test_module/src/Tests/Functional/TestModuleTest.php');

        $this->assertStringContainsString('final class TestModuleTest', $contents);
    }
}
