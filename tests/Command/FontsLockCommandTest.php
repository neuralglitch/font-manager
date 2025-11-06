<?php

declare(strict_types=1);

namespace NeuralGlitch\FontManager\Tests\Command;

use NeuralGlitch\FontManager\Command\FontsLockCommand;
use NeuralGlitch\FontManager\Provider\GoogleFontsProvider;
use NeuralGlitch\FontManager\Provider\ProviderRegistry;
use NeuralGlitch\FontManager\Service\FontDownloader;
use NeuralGlitch\FontManager\Service\FontLockManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\MockHttpClient;

final class FontsLockCommandTest extends TestCase
{
    private string $tempDir;
    private Filesystem $filesystem;

    protected function setUp(): void
    {
        $this->filesystem = new Filesystem();
        $this->tempDir = sys_get_temp_dir() . '/font-manager-test-' . uniqid();
        $this->filesystem->mkdir($this->tempDir);
    }

    protected function tearDown(): void
    {
        $this->filesystem->remove($this->tempDir);
    }

    public function testExecuteWithNoTemplates(): void
    {
        $httpClient = new MockHttpClient();
        $googleProvider = new GoogleFontsProvider($httpClient);
        $registry = new ProviderRegistry();
        $registry->registerProvider($googleProvider);

        $downloader = new FontDownloader($this->tempDir . '/fonts', $httpClient, $registry, $this->filesystem);
        $lockManager = new FontLockManager(
            $this->tempDir . '/manifest.json',
            $downloader,
            $this->filesystem
        );

        $command = new FontsLockCommand($lockManager, $this->tempDir);
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        self::assertSame(1, $commandTester->getStatusCode());
        $output = $commandTester->getDisplay();
        self::assertStringContainsString('No template directories found', $output);
    }

    public function testExecuteScansTemplates(): void
    {
        $templateDir = $this->tempDir . '/templates';
        $this->filesystem->mkdir($templateDir);
        $this->filesystem->dumpFile(
            $templateDir . '/test.html.twig',
            "{{ font_manager('Roboto', '400', 'normal') }}"
        );

        $httpClient = new MockHttpClient([
            new \Symfony\Component\HttpClient\Response\MockResponse('@font-face { src: url(https://example.com/font.woff2); }'),
            new \Symfony\Component\HttpClient\Response\MockResponse('font-data'),
        ]);
        $googleProvider = new GoogleFontsProvider($httpClient);
        $registry = new ProviderRegistry();
        $registry->registerProvider($googleProvider);

        $downloader = new FontDownloader($this->tempDir . '/fonts', $httpClient, $registry, $this->filesystem);
        $lockManager = new FontLockManager(
            $this->tempDir . '/manifest.json',
            $downloader,
            $this->filesystem
        );

        $command = new FontsLockCommand($lockManager, $this->tempDir);
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        self::assertStringContainsString('Found fonts', $output);
    }
}
