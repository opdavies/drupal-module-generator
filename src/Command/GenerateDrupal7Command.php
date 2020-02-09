<?php

namespace Opdavies\DrupalModuleGenerator\Command;

use Opdavies\DrupalModuleGenerator\Exception\CannotCreateModuleException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class GenerateDrupal7Command extends Command
{
    /** @var Filesystem */
    private $filesystem;

    /** @var Finder */
    private $finder;

    /** @var SymfonyStyle $io */
    private $io;

    private $moduleName;

    public function __construct(Finder $finder, string $name = null)
    {
        parent::__construct($name);

        $this->finder = $finder;
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
        $createdFiles = [];

        /** @var SplFileInfo $file */
        foreach ($this->finder->in('fixtures/drupal7_module')->name('/.info/') as $file) {
            $contents = $this->updateFileContents($file->getContents());

            file_put_contents(
                "{$this->moduleName}/{$this->moduleName}.{$file->getExtension()}",
                $contents
            );

            $createdFiles[] = "{$this->moduleName}.{$file->getExtension()}";
        }

        $this->io->definitionList($createdFiles);
    }

    private function updateFileContents($contents)
    {
        $contents = str_replace('{{ name }}', $this->moduleName, $contents);

        return $contents;
    }
}
