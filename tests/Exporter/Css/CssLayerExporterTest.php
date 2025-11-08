<?php

declare(strict_types=1);

namespace NeuralGlitch\FontManager\Tests\Exporter\Css;

use NeuralGlitch\FontManager\Exporter\Css\CssLayerExporter;
use NeuralGlitch\FontManager\Model\Font;
use NeuralGlitch\FontManager\Model\FontCollection;
use PHPUnit\Framework\TestCase;

final class CssLayerExporterTest extends TestCase
{
    private CssLayerExporter $exporter;

    protected function setUp(): void
    {
        $this->exporter = new CssLayerExporter();
    }

    public function testGetName(): void
    {
        $this->assertSame('css_layer', $this->exporter->getName());
    }

    public function testExportIncludesLayers(): void
    {
        $font = new Font('Ubuntu', [400], ['normal'], false, 'sans');
        $collection = new FontCollection([$font]);
        $output = $this->exporter->export($collection);

        $this->assertStringContainsString('@layer design-tokens', $output);
        $this->assertStringContainsString('@layer base', $output);
    }

    public function testExportAppliesFontsInBaseLayer(): void
    {
        $font = new Font('Ubuntu', [400], ['normal'], false, 'sans');
        $collection = new FontCollection([$font]);
        $output = $this->exporter->export($collection);

        $this->assertStringContainsString('body {', $output);
        $this->assertStringContainsString('h1, h2, h3', $output);
    }
}
