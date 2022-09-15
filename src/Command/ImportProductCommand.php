<?php
// src/Command/ImportProductCommand.php
namespace Oksana2lucky\WarehouseBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'import-product',
    description: 'Imports products list.',
    aliases: ['oksana2lucky_warehouse:import-product'],
    hidden: false
)]
class ImportProductCommand extends Command
{
    private SymfonyStyle $io;

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title('Products Importer');

        $filePath = $input->getArgument('filepath');

        if (!file_exists($filePath)) {
            return $this->fail('The file '. $filePath. ' does not exist.');
        }

        $result = ['success'];//$this->importer->run($this->createOptions($input));

        if ($result) {
            return $this->success('Products have been imported successfully.', $result);
        } else {
            return $this->fail('Fail! Products have not been imported.', $result['error'] ?? null);
        }
    }

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

    private function success(string $message, Array $result): int
    {
        $this->io->info($message);

        if (isset($result['skipped'])) {
            foreach($result['skipped'] as $skippedItem) {
                $this->io->block($skippedItem);
            }
        }

        return Command::SUCCESS;
    }

    private function fail(string $message, ?string $errorMessage = null): int
    {
        $this->io->error($message);

        if ($errorMessage) {
            $this->io->info($errorMessage);
        }

        return Command::FAILURE;
    }
}