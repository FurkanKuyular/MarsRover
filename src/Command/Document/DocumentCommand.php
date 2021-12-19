<?php

namespace App\Command\Document;

use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Console\Input\{
    InputOption, InputInterface
};
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DocumentCommand extends Command
{
    protected static $defaultName = 'api-doc:generate';

    /**
     * @var SymfonyStyle
     */
    protected SymfonyStyle $io;

    /**
     * @var OutputInterface
     */
    protected OutputInterface $output;

    /**
     * @param LoggerInterface       $logger
     * @param ContainerInterface    $container
     */
    public function __construct(
        protected LoggerInterface       $logger,
        protected ContainerInterface    $container,
    ) {

        parent::__construct(null);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create or update swagger api documentation')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->output = $output;

        $this->generateSwaggerDoc();

        return self::SUCCESS;
    }

    protected function generateSwaggerDoc(): void
    {
        $this->io->info('Document generation starting...');

        $configPath = sprintf('%s/config/swagger_doc.yaml', $this->container->getParameter('kernel.project_dir'));
        $outputPath = sprintf('%s/public/doc/swagger_config.json', $this->container->getParameter('kernel.project_dir'));

        $jsonDoc = json_encode(Yaml::parseFile($configPath));

        $filesystem = new Filesystem();

        $filesystem->dumpFile($outputPath, $jsonDoc);

        $this->io->success('Document generated successfully.');
    }
}
