<div align="center">

# Font Manager

### Universal font manager for Symfony supporting multiple providers

[![PHP Version](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat&logo=php&logoColor=white)](composer.json)
[![Symfony](https://img.shields.io/badge/Symfony-6.4+-343434?style=flat&logo=symfony&logoColor=white)](composer.json)
<br/>
[![Packagist](https://img.shields.io/badge/Packagist-abandoned-red?style=flat&logo=packagist&logoColor=white)](https://packagist.org/packages/neuralglitch/font-manager)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat)](LICENSE)

</div>

> **Abandoned and archived.** `neuralglitch/font-manager` is **abandoned** on [Packagist](https://packagist.org/packages/neuralglitch/font-manager) (replacement: [`symfinity/font-manager`](https://packagist.org/packages/symfinity/font-manager)). This [GitHub repository](https://github.com/neuralglitch/font-manager) is **read-only / archived**. New installs, issues, and releases: **[symfinity/font-manager](https://github.com/symfinity/font-manager)** (source of truth: [symfinity/symfinity](https://github.com/symfinity/symfinity) `packages/font-manager/`).
>
> **Migration:** [docs/migration.md](docs/migration.md) — Composer, namespace, Flex recipe, and bundle class changes. From `neuralglitch/google-fonts`: [docs/migration-from-google-fonts.md](docs/migration-from-google-fonts.md).

## Features

- **Multiple Providers** — Google Fonts, Bunny Fonts, Fontsource, and Local Fonts
- **Privacy-Friendly** — GDPR-compliant options (Bunny Fonts, Fontsource)
- **Development Mode** — CDN with inline styles
- **Production Mode** — Lock fonts locally for better performance and privacy
- **Multi-Format Export** — CSS, SCSS, Tailwind, TypeScript, Design Tokens, and more
- **Build Tool Support** — AssetMapper, Webpack, and Vite auto-detection
- **CLI Tools** — Search, lock, validate, prune, and export commands

## Supported Providers

| Provider | Fonts | Privacy | API Key | CDN |
|----------|-------|---------|---------|-----|
| **Google Fonts** | 1,500+ | Tracks | Optional | Yes |
| **Bunny Fonts** | 1,500+ | GDPR | No | Yes |
| **Fontsource** | 1,500+ | Good | No | Yes |
| **Local Fonts** | Custom | Perfect | No | No |

**Recommended for privacy:** Bunny Fonts (GDPR-compliant, zero tracking)

## New projects

Do **not** install this package. Use:

```bash
composer require symfinity/font-manager
```

Add the [symfinity/recipes](https://github.com/symfinity/recipes) Flex endpoint first — see [successor installation guide](https://github.com/symfinity/font-manager/blob/main/docs/installation.md).

## Documentation (legacy tree)

Historical docs for the last `neuralglitch/*` release. For current Symfinity docs, use the [successor handbook](https://github.com/symfinity/font-manager/tree/main/docs).

- **[Migration from neuralglitch/font-manager](docs/migration.md)** — upgrade to `symfinity/font-manager`
- **[Migration from neuralglitch/google-fonts](docs/migration-from-google-fonts.md)** — google-fonts successor path
- **[Export Formats](docs/exports.md)** — Multi-format export guide
- **[Usage Guide](docs/usage.md)** — Function parameters and examples
- **[Providers](docs/providers.md)** — Provider comparison and setup
- **[Commands](docs/commands.md)** — CLI command reference
- **[Configuration](docs/configuration.md)** — All configuration options
- **[Local Fonts](docs/local-fonts.md)** — Custom font setup

## Requirements (last legacy release)

- PHP 8.1 or higher
- Symfony 6.4, 7.x, or 8.x
- Twig 3.0 or higher

## Support

- **Issues / security:** [symfinity/font-manager](https://github.com/symfinity/font-manager/issues) — not this archived repo
- [Contributing](CONTRIBUTING.md) — historical; contributions go to symfinity/symfinity

## License

[MIT](LICENSE)
