<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Log\ComposerLog;

#[AsCommand(
    name: 'app:validate-composer',
    description: 'Validates a composer.json file and logs the results.'
)]
class CheckComposerCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('path', InputArgument::REQUIRED, 'The path to the composer.json file to validate.')
            ->addOption('lang', null, InputOption::VALUE_REQUIRED, 'The language for the logs', 'en');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filePath = $input->getArgument('path');
        $lang = $input->getOption('lang');

        if (!file_exists($filePath)) {
            $io->error("The file $filePath does not exist.");
            return Command::FAILURE;
        }

        $validationResults = $this->validate($filePath);

        foreach ($validationResults as $key => $isValid) {
            $message = "{$key} is " . ($isValid ? 'valid' : 'not valid');
            $this->logResult($io, $key, $message, $lang);
        }

        $io->success('Validation and logging completed.');
        return Command::SUCCESS;
    }

    private function validate($filePath): array
    {
        $composerJson = json_decode(file_get_contents($filePath), true);

        $isValidName = isset($composerJson['name']);
        $isValidDescription = isset($composerJson['description']);
        $isValidRequire = isset($composerJson['require']) && is_array($composerJson['require']);

        return [
            'name' => $isValidName,
            'description' => $isValidDescription,
            'require' => $isValidRequire
        ];
    }

    private function logResult($io, $key, $message, $lang)
    {
        try {
            ComposerLog::writeLog($key, $message, $lang);
            $io->writeln(ComposerLog::logTitle($key, $lang) . ' ' . ComposerLog::logMessage($message, $lang));
        } catch (\Exception $e) {
            $io->error($e->getMessage());
        }
    }
}
