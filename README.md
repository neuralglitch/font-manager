<div align="center">

# Font Manager

### Universal font manager for Symfony supporting multiple providers

[![PHP Version](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat&logo=php&logoColor=white)](composer.json)
[![Symfony](https://img.shields.io/badge/Symfony-6.4+-343434?style=flat&logo=symfony&logoColor=white)](composer.json)
<br/>
[![PHPUnit](https://github.com/neuralglitch/font-manager/actions/workflows/phpunit.yml/badge.svg)](https://github.com/neuralglitch/font-manager/actions/workflows/phpunit.yml)
[![Coverage](https://github.com/neuralglitch/font-manager/actions/workflows/coverage.yml/badge.svg)](https://github.com/neuralglitch/font-manager/actions/workflows/coverage.yml)
[![PHPStan](https://github.com/neuralglitch/font-manager/actions/workflows/phpstan.yml/badge.svg)](https://github.com/neuralglitch/font-manager/actions/workflows/phpstan.yml)
<br/>
[![Psalm](https://github.com/neuralglitch/font-manager/actions/workflows/psalm.yml/badge.svg)](https://github.com/neuralglitch/font-manager/actions/workflows/psalm.yml)
[![Infection](https://github.com/neuralglitch/font-manager/actions/workflows/infection.yml/badge.svg)](https://github.com/neuralglitch/font-manager/actions/workflows/infection.yml)
[![Code Style](https://github.com/neuralglitch/font-manager/actions/workflows/php-cs-fixer.yml/badge.svg)](https://github.com/neuralglitch/font-manager/actions/workflows/php-cs-fixer.yml)
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
- **Multi-Format Export** - Export fonts in 12+ formats (CSS, SCSS, Tailwind, TypeScript, Design Tokens, and more)
- **Build Tool Support** - AssetMapper, Webpack, and Vite auto-detection
- **Framework Integration** - Bootstrap SCSS variables, Tailwind config, CSS custom properties
- **Design System Ready** - W3C Design Tokens, Figma Tokens, Style Dictionary
- **Smart CSS** - Automatic font styling for body, headings, and bold text
- **CLI Tools** - Search, lock, validate, prune, and export commands
- **Custom Fonts** - Support for self-hosted brand fonts
- **Type-Safe** - PHP 8.1 enums and TypeScript definitions

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

This downloads fonts to `assets/fonts/` and automatically exports them in configured formats.

The bundle automatically switches to locked fonts in production.

### 3. Configure export formats (optional)

```yaml
# config/packages/font_manager.yaml
font_manager:
  build:
    tool: 'auto'  # auto-detect: assetmapper, webpack, or vite
  
  export:
    formats:
      - css_variables      # CSS custom properties
      - scss_bootstrap     # Bootstrap SCSS variables
      - tailwind_config    # Tailwind CSS configuration
      - typescript_definitions  # TypeScript type definitions
```

Available formats:
- **CSS**: `css_variables`, `css_modules`, `css_layer`
- **SCSS**: `scss_variables`, `scss_bootstrap`, `scss_mixins`
- **JavaScript**: `esm_javascript`, `tailwind_config`, `typescript_definitions`
- **Design System**: `json`, `design_tokens`, `figma_tokens`, `style_dictionary`

### 4. Optional: Search and export

```bash
# Search available fonts (requires API key for Google provider)
php bin/console fonts:search roboto --provider=google

# Export fonts in specific formats
php bin/console fonts:export --format=scss_bootstrap --format=tailwind_config

# List all available export formats
php bin/console fonts:formats

# Show usage instructions for a format
php bin/console fonts:format:info scss_bootstrap

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

## Multi-Format Export

Font Manager can export fonts in 12+ formats for seamless framework integration:

### Bootstrap Integration

```yaml
# config/packages/font_manager.yaml
font_manager:
  export:
    formats:
      - scss_bootstrap
```

```scss
// app.scss
@import './assets/styles/fonts-bootstrap';  // Font Manager variables
@import 'bootstrap/scss/bootstrap';         // Bootstrap uses your fonts
```

### Tailwind Integration

```yaml
font_manager:
  export:
    formats:
      - tailwind_config
```

```javascript
// tailwind.config.js
const fontConfig = require('./assets/fonts-tailwind.config.js');

module.exports = {
  theme: {
    extend: {
      fontFamily: fontConfig.fontFamily,
    },
  },
};
```

### TypeScript Integration

```yaml
font_manager:
  export:
    formats:
      - typescript_definitions
```

```typescript
// app.ts
import { fonts, type FontFamily } from './assets/fonts';

function applyFont(element: HTMLElement, family: FontFamily) {
  element.style.fontFamily = fonts[family].family; // Type-safe!
}
```

## Documentation

- **[Export Formats](docs/exports.md)** - Multi-format export guide (CSS, SCSS, Tailwind, TypeScript, Design Tokens)
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

## Development

### Quality Assurance Tools

```bash
# Quick QA check
make qa          # CS + PHPStan + Psalm + Tests

# Full QA suite
make qa-full     # QA + Mutation Testing + Metrics

# Individual tools
make phpstan     # Static analysis (PHPStan level max)
make psalm       # Static analysis (Psalm level 4)
make infection   # Mutation testing (MSI ≥40%)
make metrics     # Code quality metrics
make rector      # Automated refactoring checks
```

### Dependency Management

This package uses [Renovate](https://docs.renovatebot.com/) for automated dependency updates:
- Weekly updates (Mondays before 6am)
- Security updates at any time
- Grouped updates for Symfony packages and QA tools
- Auto-merge for minor GitHub Actions updates

For more details, see [CONTRIBUTING.md](CONTRIBUTING.md).

## Support

- [GitHub Issues](https://github.com/neuralglitch/font-manager/issues)
- [Security](https://github.com/neuralglitch/font-manager/security)
- [Contributing](CONTRIBUTING.md)

## License

[MIT](LICENSE)
