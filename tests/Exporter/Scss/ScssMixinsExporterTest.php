<?php

declare(strict_types=1);

namespace NeuralGlitch\FontManager\Tests\Exporter\Scss;

use NeuralGlitch\FontManager\Exporter\Scss\ScssMixinsExporter;
use NeuralGlitch\FontManager\Model\Font;
use NeuralGlitch\FontManager\Model\FontCollection;
use PHPUnit\Framework\TestCase;

final class ScssMixinsExporterTest extends TestCase
{
    private ScssMixinsExporter $exporter;

    protected function setUp(): void
    {
        $this->exporter = new ScssMixinsExporter();
    }

    public function testGetName(): void
    {
        $this->assertSame('scss_mixins', $this->exporter->getName());
    }

    public function testDependencies(): void
    {
        $deps = $this->exporter->getDependencies();

        $this->assertContains('scss_variables', $deps);
    }

    public function testExportIncludesMaps(): void
    {
        $font = new Font('Ubuntu', [400, 700], ['normal'], false, 'sans');
        $collection = new FontCollection([$font]);
        $output = $this->exporter->export($collection);

        $this->assertStringContainsString('$fonts:', $output);
        $this->assertStringContainsString('$font-weights:', $output);
    }

    public function testExportIncludesFunctions(): void
    {
        $font = new Font('Ubuntu', [400], ['normal'], false, 'sans');
        $collection = new FontCollection([$font]);
        $output = $this->exporter->export($collection);

        $this->assertStringContainsString('@function font-family', $output);
        $this->assertStringContainsString('@function font-weight', $output);
    }

    public function testExportIncludesMixins(): void
    {
        $font = new Font('Ubuntu', [400], ['normal'], false, 'sans');
        $collection = new FontCollection([$font]);
        $output = $this->exporter->export($collection);

        $this->assertStringContainsString('@mixin apply-font', $output);
        $this->assertStringContainsString('@mixin font', $output);
    }
}
