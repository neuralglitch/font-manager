<?php

declare(strict_types=1);

namespace NeuralGlitch\FontManager\Twig;

use NeuralGlitch\FontManager\Enum\FontDisplay;
use NeuralGlitch\FontManager\Provider\FontProviderInterface;
use NeuralGlitch\FontManager\Provider\ProviderRegistry;
use NeuralGlitch\FontManager\Service\FontVariantHelper;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Extension\RuntimeExtensionInterface;

final class FontManagerRuntime implements RuntimeExtensionInterface
{
    /** @var array<string, mixed>|null */
    private static ?array $manifestCache = null;

    public function __construct(
        private readonly ProviderRegistry $providerRegistry,
        private readonly bool $useLockedFonts,
        private readonly ?string $manifestFile = null,
        private readonly Filesystem $filesystem = new Filesystem()
    ) {
    }

    /**
     * Render fonts using specified provider or default.
     *
     * @param string                   $name      Font family name (e.g., "Ubuntu", "Roboto")
     * @param array<int|string>|string $weights   Font weights (e.g., "300 400 700" or [300, 400, 700])
     * @param array<string>|string     $styles    Font styles (e.g., "normal italic" or ["normal", "italic"])
     * @param string|null              $display   Font display value (default: "swap")
     * @param bool                     $monospace Whether this is a monospace font (default: false)
     * @param string|null              $provider  Provider name (google, bunny, local) or null for default
     *
     * @return string HTML string with font links and styles
     */
    public function renderFonts(
        string $name,
        array|string $weights = ['400'],
        array|string $styles = ['normal'],
        ?string $display = null,
        bool $monospace = false,
        ?string $provider = null
    ): string {
        // Normalize weights and styles
        $normalizedWeights = FontVariantHelper::normalizeArray($weights);
        $normalizedStylesRaw = FontVariantHelper::normalizeArray($styles);
        /** @var array<string> $normalizedStyles */
        $normalizedStyles = array_map('strval', $normalizedStylesRaw);

        $displayEnum = is_string($display) ? FontDisplay::tryFrom($display) ?? FontDisplay::SWAP : FontDisplay::SWAP;

        // Check if we should use locked fonts
        if ($this->useLockedFonts && $this->hasLockedFonts($name)) {
            return $this->renderLockedFonts($name, $monospace);
        }

        // Get the provider (use default if not specified)
        $activeProvider = null !== $provider
            ? $this->providerRegistry->getProvider($provider)
            : $this->providerRegistry->getDefaultProvider();

        return $this->renderProviderFonts(
            $activeProvider,
            $name,
            $normalizedWeights,
            $normalizedStyles,
            $displayEnum,
            $monospace
        );
    }

    /**
     * Render fonts from provider CDN (development mode).
     *
     * @param array<int|string> $weights
     * @param array<string>     $styles
     */
    private function renderProviderFonts(
        FontProviderInterface $provider,
        string $name,
        array $weights,
        array $styles,
        FontDisplay $display,
        bool $monospace
    ): string {
        $fontVar = '--font-family-'.FontVariantHelper::sanitizeFontName($name);
        $defaultWeight = ! empty($weights) ? (int) reset($weights) : 400;
        $headingWeight = $this->findWeight($weights, 500, 700);
        $boldWeight = $this->findWeight($weights, 700, 700);

        $parts = [];

        // Let provider render its own CDN links (avoids hardcoding)
        $parts[] = $provider->renderCdnLinks($name, $weights, $styles, $display);

        // Inline CSS variables and styles
        $inlineStyles = $this->generateInlineStyles($fontVar, $name, $defaultWeight, $headingWeight, $boldWeight, $monospace);
        $parts[] = sprintf('<style>%s</style>', $inlineStyles);

        return implode("\n", $parts);
    }

    /**
     * Render locked fonts (production mode).
     */
    private function renderLockedFonts(string $name, bool $monospace): string
    {
        $sanitizedName = FontVariantHelper::sanitizeFontName($name);
        $cssPath = '/assets/fonts/'.$sanitizedName.'.css';

        return sprintf('<link rel="stylesheet" href="%s">', htmlspecialchars($cssPath, ENT_QUOTES, 'UTF-8'));
    }

    /**
     * Generate inline CSS styles.
     */
    private function generateInlineStyles(
        string $fontVar,
        string $fontName,
        int $defaultWeight,
        int $headingWeight,
        int $boldWeight,
        bool $monospace
    ): string {
        $fallbackFamily = $monospace ? 'monospace' : 'sans-serif';
        $fontFamily = sprintf("'%s', %s", $fontName, $fallbackFamily);

        $css = sprintf(':root { %s: %s; }', $fontVar, $fontFamily);

        if ($monospace) {
            $css .= sprintf(' code, pre, kbd, samp { font-family: var(%s); font-weight: %d; }', $fontVar, $defaultWeight);
        } else {
            $css .= sprintf(' body { font-family: var(%s); font-weight: %d; }', $fontVar, $defaultWeight);
            $css .= sprintf(' h1, h2, h3, h4, h5, h6 { font-family: var(%s); font-weight: %d; }', $fontVar, $headingWeight);
            $css .= sprintf(' strong, b { font-weight: %d; }', $boldWeight);
        }

        return $css;
    }

    /**
     * Check if fonts are locked (manifest exists).
     */
    private function hasLockedFonts(string $name): bool
    {
        if (! $this->manifestFile || ! $this->filesystem->exists($this->manifestFile)) {
            return false;
        }

        // Load manifest if not cached
        if (null === self::$manifestCache) {
            $content = file_get_contents($this->manifestFile);
            if (false === $content) {
                return false;
            }
            $decoded = json_decode($content, true);
            self::$manifestCache = is_array($decoded) ? $decoded : [];
        }

        $fonts = self::$manifestCache['fonts'] ?? null;

        return is_array($fonts) && isset($fonts[$name]);
    }

    /**
     * Find appropriate weight from available weights.
     *
     * @param array<int|string> $weights
     */
    private function findWeight(array $weights, int $minWeight, int $fallback): int
    {
        foreach ($weights as $weight) {
            $w = (int) $weight;
            if ($w >= $minWeight) {
                return $w;
            }
        }

        return $fallback;
    }
}
