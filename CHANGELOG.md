# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.1.0] - 2024-11-04

### Added
- Initial release
- **Multiple font provider support:**
  - Google Fonts (1,500+ fonts)
  - Bunny Fonts (GDPR-compliant, zero tracking)
  - Fontsource (version-controlled, self-hosted)
  - Local Fonts (custom/brand fonts)
- **`font_manager()` Twig function** for universal font loading
- **Provider abstraction architecture** for extensibility
- **PHP 8.1 Enums** for type safety:
  - `FontDisplay` enum (swap, block, auto, optional, fallback)
  - `ProviderFeature` enum (search, metadata, cdn, variable_fonts)
  - `FontStyle` enum (normal, italic)
- **CLI commands:**
  - `fonts:migrate-from-google-fonts` - Auto-migrate from google-fonts bundle
  - `fonts:search` - Search for fonts across providers
  - `fonts:lock` - Lock fonts for production
  - `fonts:status` - Show locked fonts status
  - `fonts:validate` - Validate local font files
  - `fonts:prune` - Remove unused fonts
- **Comprehensive documentation:**
  - Usage guide with examples
  - Provider comparison and setup
  - Commands reference
  - Configuration guide
  - Local fonts guide
  - Migration guide
- **Quality assurance:**
  - 143 tests with 89.44% code coverage
  - **PHPStan** level max (zero errors)
  - **Psalm** level 4 static analysis
  - **Infection** mutation testing (MSI ≥40%, Covered MSI ≥45%)
  - **PHPMetrics** code quality metrics
  - **Rector** automated refactoring checks
  - **PHP-CS-Fixer** PSR-12 coding standards
  - **Renovate** automated dependency updates
  - Symfony Flex recipe
  - GitHub Actions CI/CD (Tests, Coverage, Psalm, Infection, Code Style)
- **AssetMapper integration** for asset management

### Features
- Support for 1,500+ Google Fonts
- Support for 1,500+ Bunny Fonts (privacy-friendly)
- Custom local font support
- Development mode (CDN)
- Production mode (self-hosted/locked fonts)
- Automatic font styling (body, headings, code)
- Font lock manifest
- Template scanning
- Provider registry
- Specialized exceptions

## [1.0.0] - TBD

Initial release.

---

## Migration from google-fonts Bundle

If migrating from `neuralglitch/google-fonts`:

### Breaking Changes
- Package name changed: `neuralglitch/google-fonts` → `neuralglitch/font-manager`
- Twig function renamed: `google_fonts()` → `font_manager()`
- Commands renamed: `gfonts:*` → `fonts:*`
- Configuration key changed: `google_fonts` → `font_manager`

### Migration Steps

1. Update composer:
```bash
composer remove neuralglitch/google-fonts
composer require neuralglitch/font-manager
```

2. Update configuration:
```yaml
# OLD: config/packages/google_fonts.yaml
# NEW: config/packages/font_manager.yaml
```

3. Update templates:
```twig
<!-- OLD -->
{{ google_fonts('Roboto', '400 700') }}

<!-- NEW -->
{{ font_manager('Roboto', '400 700') }}
```

4. Update commands:
```bash
# OLD
php bin/console gfonts:lock

# NEW
php bin/console fonts:lock
```

---

[Unreleased]: https://github.com/neuralglitch/font-manager/compare/main...HEAD

