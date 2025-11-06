# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.1.0] - 2025-11-06

### Added

- Initial release of Font Manager Bundle for Symfony
- Multi-provider architecture supporting Google Fonts, Bunny Fonts, Fontsource, and Local Fonts
- Twig function `font_manager()` for easy font integration in templates with optimized parameter order
- Development mode with provider CDN and inline styles
- Production mode with local font locking and dedicated stylesheets
- Automatic CSS variable generation for font families (`--font-{name}`)
- Intelligent CSS rules for body, headings, bold text, and italic styles
- Separate CSS rules for monospace fonts (only `code`, `pre`, `kbd`, `samp` tags)
- PHP 8.1 Enums for type-safe configuration:
  - `FontDisplay` (auto, block, swap, fallback, optional)
  - `ProviderFeature` (search, metadata, variable_fonts, cdn)
  - `FontStyle` (normal, italic)
- Unicode subset filtering with configurable subsets via YAML:
  - Default: `['latin', 'latin-ext']` (reduces file count by ~83%)
  - Configurable: Add `cyrillic`, `greek`, etc. for international projects
  - Automatic detection and filtering for Google Fonts, Bunny Fonts, and Fontsource
- Subset-based filename generation:
  - Format: `{font}-{weight}-{subset}-{style}.{ext}`
  - Examples: `ubuntu-400-latin.woff2`, `ubuntu-mono-400-latin-ext-italic.woff2`
  - Works with all providers (Google, Bunny, Fontsource)
- Provider abstraction layer with feature detection
- Console commands:
  - `fonts:search` - Search fonts from any provider
  - `fonts:lock` - Scan templates and lock all used fonts locally
  - `fonts:validate` - Validate local font configuration
  - `fonts:status` - Display configuration and locked fonts status
  - `fonts:prune` - Remove unused fonts from locked fonts directory
  - `fonts:migrate-from-google-fonts` - Automated migration from google-fonts bundle
- Font manifest file for production font management
- Support for multiple weights (100-900) and styles (normal, italic)
- Monospace font support with dedicated CSS rules (no duplicate italic styles)
- Font variant helper for consistent font name sanitization
- Provider-specific features:
  - Google Fonts: API search, metadata, variable fonts, CDN
  - Bunny Fonts: GDPR-compliant CDN (no API key required)
  - Fontsource: Privacy-friendly CDN with jsdelivr, automatic lowercase-kebab-case conversion, relative URL resolution
  - Local Fonts: Self-hosted custom fonts with YAML configuration
- Environment-aware font loading (CDN in dev, locked in prod)
- Automatic font subsetting and optimization during locking
- Automatic provider detection and tracking in manifest
- Provider registry for dynamic provider management
- Comprehensive exception handling with dedicated exception classes
- Full PHP 8.1+ type safety with strict types
- PHPStan Level 9 compliance (max level)
- Psalm Level 4 compliance (>93% type coverage)
- Infection Mutation Testing (MSI ≥40%, Covered MSI ≥45%)
- Symfony 6.4, 7.x, and 8.x compatibility
- PHP 8.1-8.4 support in CI/CD pipeline
- Comprehensive test suite with 143 tests and >93% code coverage
- AssetMapper integration with proper font path handling
- Symfony Flex recipe support
- Documentation:
  - Usage guide with examples
  - Provider comparison and setup instructions
  - CLI command reference
  - Complete configuration reference
  - Local fonts setup guide
  - Migration guide from google-fonts bundle

### Quality Assurance

- PHPStan static analysis at maximum level (level 9)
- Psalm static analysis with INFO level reporting
- PHP CS Fixer with PSR-12 and Symfony coding standards
- Rector automated refactoring checks
- PHPMetrics code quality metrics
- Mutation testing with Infection
- GitHub Actions workflows:
  - PHPUnit tests on PHP 8.1-8.4
  - Code coverage reporting via Codecov
  - PHPStan analysis on PHP 8.1
  - Psalm analysis on PHP 8.1-8.4
  - Infection mutation testing on PHP 8.1
  - PHP CS Fixer validation
- Renovate dependency management:
  - Weekly automated updates
  - Security updates at any time
  - Grouped updates for Symfony and QA tools
  - Auto-merge for minor GitHub Actions updates

### Developer Experience

- Make commands for quick quality checks (`make qa`, `make qa-full`)
- Comprehensive CONTRIBUTING.md with development guidelines
- Clear README with quick start examples
- Detailed inline documentation and PHPDocs
- Type-safe API with full IDE autocompletion support
- Helpful CLI output with tables, progress bars, and colored formatting
- Dry-run mode for migration command to preview changes safely

