<div align="center">

# Font Manager

### Universal font manager for Symfony supporting multiple providers

[![PHP Version](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat&logo=php&logoColor=white)](composer.json)
[![Symfony](https://img.shields.io/badge/Symfony-6.4+-343434?style=flat&logo=symfony&logoColor=white)](composer.json)
<br/>
[![Tests](https://github.com/neuralglitch/font-manager/actions/workflows/tests.yml/badge.svg?style=flat&logo=github)](https://github.com/neuralglitch/font-manager/actions/workflows/tests.yml)
[![Code Coverage](https://img.shields.io/badge/PHPUnit-93.75%25-32c252?style=flat&logo=codecov&logoColor=white)](build/coverage/index.html)
[![Static Analysis](https://github.com/neuralglitch/font-manager/actions/workflows/static-analysis.yml/badge.svg?style=flat&logo=github)](https://github.com/neuralglitch/font-manager/actions/workflows/static-analysis.yml)
[![PHPStan Level](https://img.shields.io/badge/PHPStan-level%20max-32c252?style=flat&logo=php&logoColor=white)](phpstan.neon.dist)
<br/>
[![Release](https://img.shields.io/packagist/v/neuralglitch/font-manager.svg?style=flat&logo=packagist&logoColor=white)](https://packagist.org/packages/neuralglitch/font-manager)
[![Downloads](https://img.shields.io/packagist/dt/neuralglitch/font-manager.svg?style=flat&logo=packagist&logoColor=white)](https://packagist.org/packages/neuralglitch/font-manager)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat)](LICENSE)

</div>

## Features

- **Multiple Providers** - Google Fonts, Bunny Fonts, Fontsource, and Local Fonts
- **Privacy-Friendly** - GDPR-compliant options (Bunny Fonts, Fontsource)
- **Development Mode** - CDN with inline styles
- **Production Mode** - Lock fonts locally for better performance and privacy
- **Smart CSS** - Automatic font styling for body, headings, and bold text
- **CLI Tools** - Search, lock, validate, and prune commands
- **Custom Fonts** - Support for self-hosted brand fonts
- **Type-Safe** - PHP 8.1 enums for display and features

## Supported Providers

| Provider | Fonts | Privacy | API Key | CDN |
|----------|-------|---------|---------|-----|
| **Google Fonts** | 1,500+ | ⚠️ Tracks | Optional | ✅ |
| **Bunny Fonts** | 1,500+ | ✅ GDPR | No | ✅ |
| **Fontsource** | 1,500+ | ✅ Good | No | ✅ |
| **Local Fonts** | Custom | ✅ Perfect | No | ❌ |

**Recommended for privacy:** Use **Bunny Fonts** (GDPR-compliant, zero tracking)

## Prerequisites

For fully automatic setup, visit the [related Flex recipe repository](https://github.com/neuralglitch/symfony-recipes) and follow the instructions to add it to the
composer.json in the consuming project, as the recipe is not yet part of the Symfony’s main recipe repository.

## Installation

```bash
composer require neuralglitch/font-manager
```

## Quick Start

### 1. Add fonts to your template

```twig
{# templates/base.html.twig #}
<head>
  {# Use default provider (Bunny Fonts recommended for privacy) #}
  {{ font_manager('Ubuntu', '300 400 700', 'normal italic') }}
  
  {# Monospace font for code #}
  {{ font_manager('JetBrains Mono', '400 500', 'normal', 'swap', true) }}
</head>
```

### 2. Lock fonts for production

```bash
php bin/console fonts:lock
```

This downloads fonts to `assets/fonts/` (served by AssetMapper in dev, compiled to `public/` in prod).

The bundle automatically switches to locked fonts in production.

### 3. Optional: Search for fonts

```bash
# Search available fonts (requires API key for Google provider)
php bin/console fonts:search roboto --provider=google

# Validate local fonts
php bin/console fonts:validate
```

## Configuration

```yaml
# config/packages/font_manager.yaml
font_manager:
    default_provider: 'bunny'  # Recommended: privacy-friendly
    
    providers:
        bunny:
            enabled: true  # GDPR-compliant, zero tracking
```

For detailed configuration options, see [Configuration Guide](docs/configuration.md).

## Migration from google-fonts

Migrating from `neuralglitch/google-fonts`? Use the automatic migration command:

```bash
php bin/console fonts:migrate-from-google-fonts --dry-run  # Preview
php bin/console fonts:migrate-from-google-fonts            # Apply
```

See [Migration Guide](docs/migration.md) for details.

## Documentation

- **[Usage Guide](docs/usage.md)** - Function parameters and examples
- **[Providers](docs/providers.md)** - Provider comparison and setup
- **[Commands](docs/commands.md)** - CLI command reference
- **[Configuration](docs/configuration.md)** - All configuration options
- **[Local Fonts](docs/local-fonts.md)** - Custom font setup
- **[Migration Guide](docs/migration.md)** - Migrating from google-fonts

## Requirements

- PHP 8.1 or higher
- Symfony 6.4, 7.x, or 8.x
- Twig 3.0 or higher

## Support

- [GitHub Issues](https://github.com/neuralglitch/font-manager/issues)
- [Security](https://github.com/neuralglitch/font-manager/security)
- [Contributing](CONTRIBUTING.md)

## License

[MIT](LICENSE)
