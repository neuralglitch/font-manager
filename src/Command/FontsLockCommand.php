<?php

declare(strict_types=1);

namespace NeuralGlitch\FontManager\Command;

use NeuralGlitch\FontManager\Service\FontLockManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'fonts:lock',
    description: 'Scan templates and lock all used fonts for production'
)]
final class FontsLockCommand extends Command
{
    public function __construct(
        private readonly FontLockManager $lockManager,
        private readonly string $projectDir
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'template-dirs',
                InputArgument::IS_ARRAY | InputArgument::OPTIONAL,
                'Template directories to scan',
                []
            )
            ->setHelp(
                'The <info>%command.name%</info> command scans Twig templates for font_manager() function calls, ' .
                'downloads all referenced fonts, and creates a manifest file for production use.' . "\n\n" .
                'Example: <info>php %command.full_name%</info>'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Lock Fonts');

        $templateDirsArg = $input->getArgument('template-dirs');
        $templateDirs = is_array($templateDirsArg) ? $templateDirsArg : [];

        // Default to common template directories
        if ([] === $templateDirs) {
            $defaultDirs = [
                $this->projectDir . '/templates',
                $this->projectDir . '/views',
            ];
            $templateDirs = array_filter($defaultDirs, 'is_dir');

            if ([] === $templateDirs) {
                $io->error('No template directories found. Please specify template directories as arguments.');

                return Command::FAILURE;
            }
        }

        $templateDirs = array_filter($templateDirs, 'is_string');

        $io->section('Scanning templates');
        $io->listing(array_map(fn (string $dir): string => "<info>{$dir}</info>", $templateDirs));

        $fonts = $this->lockManager->scanTemplates($templateDirs);

        if ([] === $fonts) {
            $io->warning('No font_manager() function calls found in templates.');

            return Command::SUCCESS;
        }

        $io->section('Found fonts');
        $fontList = [];
        /** @var array<array-key, mixed> $fonts */
        foreach ($fonts as $name => $config) {
            if (!is_array($config)) {
                continue;
            }
            /** @var array{weights?: array<int|string>, styles?: array<string>} $config */
            $weights = implode(', ', $config['weights'] ?? []);
            $styles = implode(', ', $config['styles'] ?? []);
            $fontList[] = sprintf('<info>%s</info> (weights: %s, styles: %s)', $name, $weights, $styles);
        }
        $io->listing($fontList);

        $io->section('Downloading fonts');
        $io->progressStart(count($fonts));

        $this->lockManager->lockFonts($fonts, function (int $current, int $total, string $name) use ($io): void {
            $io->progressAdvance();
        });

        $io->progressFinish();

        $io->success(sprintf('Successfully locked %d fonts to %s', count($fonts), $this->lockManager->getManifestFile()));

        return Command::SUCCESS;
    }
}
