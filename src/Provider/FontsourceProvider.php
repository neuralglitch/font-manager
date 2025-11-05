<?php

declare(strict_types=1);

namespace NeuralGlitch\FontManager\Provider;

use NeuralGlitch\FontManager\Enum\FontDisplay;
use NeuralGlitch\FontManager\Exception\ProviderException;

/**
 * Fontsource provider - Self-hosted Google Fonts via npm packages and CDN
 * Uses jsdelivr or unpkg CDN to access @fontsource packages without npm.
 */
final class FontsourceProvider extends AbstractProvider
{
    private const CDN_BASE = 'https://cdn.jsdelivr.net/npm';
    private const NPM_REGISTRY = 'https://registry.npmjs.org';

    protected const FEATURES = [
        'search' => true,
        'metadata' => true,
        'variable_fonts' => true,
        'cdn' => true,
    ];

    public function getName(): string
    {
        return 'fontsource';
    }

    public function searchFonts(string $query, int $maxResults = 20): array
    {
        // Search npm registry for @fontsource packages
        $response = $this->httpClient->request('GET', self::NPM_REGISTRY.'/-/v1/search', [
            'query' => [
                'text' => '@fontsource/'.$query,
                'size' => $maxResults,
            ],
        ]);

        $data = $response->toArray();
        $results = [];

        foreach ($data['objects'] ?? [] as $object) {
            $package = $object['package'] ?? [];
            $name = $package['name'] ?? '';

            // Extract font name from @fontsource/font-name
            if (str_starts_with((string) $name, '@fontsource/')) {
                $fontName = substr((string) $name, 12); // Remove '@fontsource/' prefix

                $results[] = [
                    'family' => $fontName,
                    'category' => 'unknown', // npm doesn't provide category
                    'variants' => ['regular'], // Simplified, would need to fetch package details
                ];
            }
        }

        return array_slice($results, 0, $maxResults);
    }

    public function getFontMetadata(string $fontName): ?array
    {
        $packageName = '@fontsource/'.$fontName;

        try {
            $response = $this->httpClient->request('GET', self::NPM_REGISTRY.'/'.$packageName);
            $data = $response->toArray();

            return [
                'family' => $fontName,
                'provider' => 'fontsource',
                'category' => 'unknown',
                'variants' => ['regular'], // Simplified
                'version' => $data['dist-tags']['latest'] ?? 'unknown',
                'description' => $data['description'] ?? '',
                'license' => $data['license'] ?? 'unknown',
            ];
        } catch (\Exception) {
            return null;
        }
    }

    public function getFontVariants(string $fontName): array
    {
        // Return default variants (Fontsource has standardized weights)
        return [
            'weights' => [100, 200, 300, 400, 500, 600, 700, 800, 900],
            'styles' => ['normal', 'italic'],
        ];
    }

    public function downloadFontCss(
        string $fontName,
        array $weights,
        array $styles,
        FontDisplay $display = FontDisplay::SWAP
    ): string {
        $version = $this->getLatestVersion($fontName);
        $packageName = '@fontsource/'.$fontName;
        $css = '';

        // Download CSS for each weight (Fontsource has separate CSS per weight)
        foreach ($weights as $weight) {
            try {
                $url = sprintf(
                    '%s/%s@%s/%s.css',
                    self::CDN_BASE,
                    $packageName,
                    $version,
                    $weight
                );

                $response = $this->httpClient->request('GET', $url);
                $css .= $response->getContent()."\n";
            } catch (\Exception) {
                // Skip if weight not available
                continue;
            }
        }

        if ('' === $css || '0' === $css) {
            throw new ProviderException(sprintf('Failed to download CSS for font "%s" from Fontsource. Font may not be available or weights may not exist.', $fontName));
        }

        return $css;
    }

    public function renderCdnLinks(
        string $fontName,
        array $weights,
        array $styles,
        FontDisplay $display = FontDisplay::SWAP
    ): string {
        $version = $this->getLatestVersion($fontName);
        $packageName = '@fontsource/'.$fontName;
        $parts = [];

        // Preconnect to jsdelivr
        $parts[] = '<link rel="preconnect" href="https://cdn.jsdelivr.net">';

        // Add stylesheet link for each weight
        foreach ($weights as $weight) {
            $url = sprintf(
                '%s/%s@%s/%s.css',
                self::CDN_BASE,
                $packageName,
                $version,
                $weight
            );
            $parts[] = sprintf('<link rel="stylesheet" href="%s">', htmlspecialchars($url, ENT_QUOTES, 'UTF-8'));
        }

        return implode("\n", $parts);
    }

    /**
     * Get latest version of Fontsource package from npm registry.
     */
    private function getLatestVersion(string $fontName): string
    {
        $packageName = '@fontsource/'.$fontName;
        $cacheKey = 'fontsource_version_'.$fontName;

        // Check cache
        $cached = $this->getFromCache($cacheKey);
        if (null !== $cached && is_string($cached)) {
            return $cached;
        }

        try {
            $response = $this->httpClient->request('GET', self::NPM_REGISTRY.'/'.$packageName);
            $data = $response->toArray();
            $version = $data['dist-tags']['latest'] ?? 'latest';

            // Cache the version
            $this->putInCache($cacheKey, $version);

            return $version;
        } catch (\Exception) {
            // Fallback to 'latest' if can't fetch version
            return 'latest';
        }
    }
}
