<?php

namespace Oksana2lucky\WarehouseBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Oksana2lucky\WarehouseBundle\Import\Importer;

#[AsCommand(
    name: 'import-product',
    description: 'Imports products list.',
    aliases: ['oksana2lucky_warehouse:import-product'],
    hidden: false
)]
class ImportProductCommand extends Command
{
    /**
     * @var SymfonyStyle
     */
    private SymfonyStyle $io;

    /**
     * @var Importer
     */
    private $importer;

    /**
     * @param Importer $importer
     */
    public function __construct(Importer $importer)
    {
        parent::__construct();

        $this->importer = $importer;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->addArgument('filepath', InputArgument::REQUIRED, 'The file path to import.')
            ->addOption(
                'no-db',
                null,
                InputOption::VALUE_NONE,
                'If no-db, the task will not save parsed data to the database.'
            );

        $this->setHelp(
            <<<'EOF'
            The <info>%command.name%</info> allows you to import a list of products.:
              <info>php bin/console %command.full_name% filepath</info>
              
            If you don't want to save data to the database set no-db option as true:
              <info>php %command.full_name% --no-db=true</info>
            EOF
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title('Products Importer');

        $filePath = $input->getArgument('filepath');

        if (!file_exists($filePath)) {
            return $this->fail('The file '. $filePath. ' does not exist.');
        }

        $options = $this->createOptions($input);

        $this->importer->run($options['filepath'], $options['no-db'] ?? false);
        $result = $this->importer->getResult();

        if ($result) {
            return $this->success('Products have been imported successfully.', $result);
        } else {
            return $this->fail('Fail! Products have not been imported.', $result['error'] ?? null);
        }
    }

    /**
     * @param InputInterface $input
     * @return array
     */
    private function createOptions(InputInterface $input): array
    {
        if ($input->hasArgument('filepath')) {
            $options['filepath'] = $input->getArgument('filepath');
        }

        if ($input->hasOption('no-db')) {
            $options['no-db'] = $input->getOption('no-db');
        }

        return array_filter($options);
    }

    private function success(string $message, array $result): int
    {
        $this->io->info($message);

        $skippedData = $this->importer->getDataHandler()->getFailData();

        $this->io->block('All Items: '.
            count($this->importer->getDataHandler()->getParsedData()));

        $this->io->block('Items imported succesfully: '.
            count($this->importer->getDataHandler()->getValidData()));

        $this->io->block('Failed items ('. count($this->importer->getDataHandler()->getFailData()) .'): ');
        foreach ($skippedData as $skippedItem) {
            $this->io->block(implode(', ', $skippedItem));
        }

        return Command::SUCCESS;
    }

    /**
     * @param string $message
     * @param string|null $errorMessage
     * @return int
     */
    private function fail(string $message, ?string $errorMessage = null): int
    {
        $this->io->error($message);

        if ($errorMessage) {
            $this->io->info($errorMessage);
        }

        return Command::FAILURE;
    }
}
