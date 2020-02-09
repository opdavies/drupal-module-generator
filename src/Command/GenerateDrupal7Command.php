<?php

namespace Opdavies\DrupalModuleGenerator\Command;

use Opdavies\DrupalModuleGenerator\Exception\CannotCreateModuleException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateDrupal7Command extends Command
{
    private $moduleName;

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
        $this->moduleName = $input->getArgument('module-name');

        $this->ensureDirectoryDoesNotExist();

        mkdir($this->moduleName);

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
    }
}
