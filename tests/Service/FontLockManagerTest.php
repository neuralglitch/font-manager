<?php

declare(strict_types=1);

namespace NeuralGlitch\FontManager\Tests\Service;

use NeuralGlitch\FontManager\Provider\GoogleFontsProvider;
use NeuralGlitch\FontManager\Provider\ProviderRegistry;
use NeuralGlitch\FontManager\Service\FontDownloader;
use NeuralGlitch\FontManager\Service\FontLockManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class FontLockManagerTest extends TestCase
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

    public function testScanTemplatesFindsFont(): void
    {
        $templateDir = $this->tempDir . '/templates';
        $this->filesystem->mkdir($templateDir);
        $this->filesystem->dumpFile(
            $templateDir . '/base.html.twig',
            "{{ font_manager('Roboto', '400 700', 'normal') }}"
        );

        $httpClient = new MockHttpClient();
        $googleProvider = new GoogleFontsProvider($httpClient);
        $registry = new ProviderRegistry();
        $registry->registerProvider($googleProvider);

        $downloader = new FontDownloader($this->tempDir . '/fonts', $httpClient, $registry, $this->filesystem);
        $manager = new FontLockManager(
            $this->tempDir . '/manifest.json',
            $downloader,
            $this->filesystem
        );

        $fonts = $manager->scanTemplates($templateDir);

        self::assertArrayHasKey('Roboto', $fonts);
        self::assertContains('400', $fonts['Roboto']['weights']);
        self::assertContains('700', $fonts['Roboto']['weights']);
    }

    public function testLockFontsCreatesManifest(): void
    {
        $cssResponse = new MockResponse('@font-face { src: url(https://example.com/font.woff2); }');
        $fontResponse = new MockResponse('fake-font-data');

        $httpClient = new MockHttpClient([$cssResponse, $fontResponse]);
        $googleProvider = new GoogleFontsProvider($httpClient);
        $registry = new ProviderRegistry();
        $registry->registerProvider($googleProvider);

        $downloader = new FontDownloader($this->tempDir . '/fonts', $httpClient, $registry, $this->filesystem);
        $manifestFile = $this->tempDir . '/manifest.json';

        $manager = new FontLockManager(
            $manifestFile,
            $downloader,
            $this->filesystem
        );

        $fonts = [
            'Roboto' => [
                'weights' => [400],
                'styles' => ['normal'],
            ],
        ];

        $manifest = $manager->lockFonts($fonts);

        self::assertTrue($this->filesystem->exists($manifestFile));
        self::assertArrayHasKey('locked', $manifest);
        self::assertTrue($manifest['locked']);
        self::assertArrayHasKey('fonts', $manifest);
        self::assertIsArray($manifest['fonts']);
        self::assertArrayHasKey('Roboto', $manifest['fonts']);
    }

    public function testGetManifestFile(): void
    {
        $httpClient = new MockHttpClient();
        $googleProvider = new GoogleFontsProvider($httpClient);
        $registry = new ProviderRegistry();
        $registry->registerProvider($googleProvider);

        $downloader = new FontDownloader($this->tempDir . '/fonts', $httpClient, $registry, $this->filesystem);
        $manifestFile = $this->tempDir . '/manifest.json';

        $manager = new FontLockManager(
            $manifestFile,
            $downloader,
            $this->filesystem
        );

        self::assertSame($manifestFile, $manager->getManifestFile());
    }
}
