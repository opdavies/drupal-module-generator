<?php

namespace Opdavies\DrupalModuleGenerator\Command;

use Opdavies\DrupalModuleGenerator\Exception\CannotCreateModuleException;
use Opdavies\DrupalModuleGenerator\Service\TestNameConverter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Tightenco\Collect\Support\Collection;

class GenerateDrupal7Command extends Command
{
    private $moduleName;
    private $testName;

    /** @var Filesystem */
    private $filesystem;

    /** @var Finder */
    private $finder;

    /** @var SymfonyStyle $io */
    private $io;

    /** @var TestNameConverter */
    private $testNameConverter;

    public function __construct(
        Finder $finder,
        TestNameConverter $testNameConverter,
        string $name = null
    ) {
        parent::__construct($name);

        $this->finder = $finder;
        $this->testNameConverter = $testNameConverter;
    }

    /**
     * {@inheritdoc}
     */
    protected static $defaultName = 'generate-drupal-7-module';

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

        $this->moduleName = $input->getArgument('module-name');
        $this->testName = $this->testNameConverter->__invoke($this->moduleName);

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
        if (is_dir($this->moduleName)) {
            throw CannotCreateModuleException::directoryAlreadyExists();
        }

        return $this;
    }

    private function createModuleDirectory()
    {
        mkdir($this->moduleName);

        return $this;
    }

    private function createFiles()
    {
        $createdFiles = new Collection();
        $testNameConverter = new TestNameConverter();

        /** @var SplFileInfo $file */
        foreach ($this->finder->in('fixtures/drupal7_module')->files() as $file) {
            $filename = "{$this->moduleName}.{$file->getExtension()}";

            if ($file->getRelativePath()) {
                mkdir("{$this->moduleName}/{$file->getRelativePath()}", 0777, $recursive = true);

                $filename = "{$this->testName}.php";
                $filename = "{$file->getRelativePath()}/{$filename}";
            }

            $contents = $this->updateFileContents($file->getContents());

            file_put_contents("{$this->moduleName}/{$filename}", $contents);

            $createdFiles->push($filename);
        }

        $this->io->listing($createdFiles->filter()->sort()->toArray());
    }

    private function updateFileContents($contents)
    {
        $contents = str_replace('{{ name }}', $this->moduleName, $contents);
        $contents = str_replace('{{ test_name }}', $this->testName, $contents);

        return $contents;
    }
}
