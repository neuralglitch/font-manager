<?php

declare(strict_types=1);

namespace NeuralGlitch\FontManager\Tests\Exporter\Css;

use NeuralGlitch\FontManager\Exporter\Css\CssModulesExporter;
use NeuralGlitch\FontManager\Model\Font;
use NeuralGlitch\FontManager\Model\FontCollection;
use PHPUnit\Framework\TestCase;

final class CssModulesExporterTest extends TestCase
{
    private CssModulesExporter $exporter;

    protected function setUp(): void
    {
        $this->exporter = new CssModulesExporter();
    }

    public function testGetName(): void
    {
        $this->assertSame('css_modules', $this->exporter->getName());
    }

    public function testGetFileExtension(): void
    {
        $this->assertSame('.module.css', $this->exporter->getFileExtension());
    }

    public function testDependencies(): void
    {
        $deps = $this->exporter->getDependencies();

        $this->assertContains('css_variables', $deps);
    }

    public function testExportIncludesExportBlock(): void
    {
        $font = new Font('Ubuntu', [400], ['normal'], false, 'sans');
        $collection = new FontCollection([$font]);
        $output = $this->exporter->export($collection);

        $this->assertStringContainsString(':export {', $output);
        $this->assertStringContainsString('fontSans:', $output);
        $this->assertStringContainsString('@import', $output);
    }
}
