<?php

namespace Opdavies\DrupalModuleGenerator\Command;

use Opdavies\DrupalModuleGenerator\Exception\CannotCreateModuleException;
use Opdavies\DrupalModuleGenerator\Service\ModuleNameConverter;
use Opdavies\DrupalModuleGenerator\Service\TestNameConverter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Tightenco\Collect\Support\Collection;

final class GenerateDrupal7Command extends Command
{
    private $moduleName;
    private $machineName;
    private $testName;

    private $finder;
    private $io;
    private $moduleNameConverter;
    private $testNameConverter;

    public function __construct(
        Finder $finder,
        ModuleNameConverter $moduleNameConverter,
        TestNameConverter $testNameConverter,
        string $name = null
    ) {
        parent::__construct($name);

        $this->finder = $finder;
        $this->moduleNameConverter = $moduleNameConverter;
        $this->testNameConverter = $testNameConverter;
    }

    /**
     * {@inheritdoc}
     */
    protected static $defaultName = 'generate:drupal-7-module';

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Generate a new Drupal 7 module.')
            ->addArgument('module-name', InputArgument::REQUIRED, 'The name of the module to create');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);

        $this->io->title("{$this->getApplication()->getName()} (D7)");

        $this->machineName = $input->getArgument('module-name');
        $this->moduleName = $this->moduleNameConverter->__invoke($this->machineName);
        $this->testName = $this->testNameConverter->__invoke($this->machineName);

        $this
            ->ensureDirectoryDoesNotExist()
            ->createModuleDirectory()
            ->createFiles();

        return 0;
    }

    /**
     * Ensure that the directory name for the module doesn't already exist.
     */
    private function ensureDirectoryDoesNotExist()
    {
        if (is_dir($this->machineName)) {
            throw CannotCreateModuleException::directoryAlreadyExists();
        }

        return $this;
    }

    private function createModuleDirectory()
    {
        mkdir($this->machineName);

        return $this;
    }

    private function createFiles()
    {
        $createdFiles = new Collection();

        /** @var SplFileInfo $file */
        foreach ($this->finder->in(__DIR__.'/../../fixtures/drupal7_module')->files() as $file) {
            $filename = "{$this->machineName}.{$file->getExtension()}";

            if ($file->getRelativePath()) {
                mkdir("{$this->machineName}/{$file->getRelativePath()}", 0777, $recursive = true);

                $filename = "{$this->testName}.php";
                $filename = "{$file->getRelativePath()}/{$filename}";
            }

            $contents = $this->updateFileContents($file->getContents());

            file_put_contents("{$this->machineName}/{$filename}", $contents);

            $createdFiles->push($filename);
        }

        if ($createdFiles->isNotEmpty()) {
            $this->io->block('Files generated:');

            $this->io->listing($createdFiles->sort()->toArray());
        }
    }

    private function updateFileContents($contents)
    {
        $contents = str_replace('{{ machine_name }}', $this->machineName, $contents);
        $contents = str_replace('{{ name }}', $this->moduleName, $contents);
        $contents = str_replace('{{ test_name }}', $this->testName, $contents);

        return $contents;
    }
}
