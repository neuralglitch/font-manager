# Migration from neuralglitch/font-manager

**`neuralglitch/font-manager` is abandoned** on Packagist (replacement: **`symfinity/font-manager`**). This repository is **archived** on GitHub — use [symfinity/font-manager](https://github.com/symfinity/font-manager) for issues, releases, and documentation.

## Package identity

| Item | Legacy (`neuralglitch/*`) | Symfinity (`symfinity/*`) |
|------|---------------------------|---------------------------|
| Composer name | `neuralglitch/font-manager` | `symfinity/font-manager` |
| GitHub | [neuralglitch/font-manager](https://github.com/neuralglitch/font-manager) (archived) | [symfinity/font-manager](https://github.com/symfinity/font-manager) |
| PSR-4 namespace | `NeuralGlitch\FontManager\` | `Symfinity\FontManager\` |
| Test namespace | `NeuralGlitch\FontManager\Tests\` | `Symfinity\FontManager\Tests\` |
| Bundle class | `NeuralGlitch\FontManager\FontManagerBundle` | `Symfinity\FontManager\FontManagerBundle` |
| Config root key | `font_manager:` | `font_manager:` (unchanged) |
| Config file | `config/packages/font_manager.yaml` | `config/packages/font_manager.yaml` |

## Composer and Symfony floor

| Constraint | Legacy (last release) | Symfinity |
|------------|----------------------|-----------|
| PHP | `>=8.1` | `>=8.2` |
| Symfony | `^6.4 \|\| ^7.0 \|\| ^8.0` | `^7.4` (org consumer floor) |

## Application changes

1. **Require** the successor and remove the legacy package:

   ```bash
   composer remove neuralglitch/font-manager
   composer require symfinity/font-manager
   ```

2. **Flex recipes** — add the [symfinity/recipes](https://github.com/symfinity/recipes) endpoint to your project's `composer.json` (see [recipes README](https://github.com/symfinity/recipes/blob/main/README.md)). Legacy installs used [neuralglitch/symfony-recipes](https://github.com/neuralglitch/symfony-recipes).

3. **Update imports** in PHP and tests: `NeuralGlitch\FontManager` → `Symfinity\FontManager`.

4. **Update `config/bundles.php`** if the bundle is registered manually:

   ```php
   // Before
   NeuralGlitch\FontManager\FontManagerBundle::class => ['all' => true],

   // After
   Symfinity\FontManager\FontManagerBundle::class => ['all' => true],
   ```

5. **Twig** — `font_manager()` is unchanged.

6. **CLI** — command names unchanged (`fonts:lock`, `fonts:search`, `fonts:status`, `fonts:prune`, …).

7. **Locked fonts** — if needed, re-lock after migration:

   ```bash
   php bin/console fonts:lock
   ```

## Migrating from google-fonts instead?

If you still use **`neuralglitch/google-fonts`**, see [Migration from neuralglitch/google-fonts](migration-from-google-fonts.md).

## Successor documentation

Full handbook for `symfinity/font-manager`:

- [Quickstart](https://github.com/symfinity/font-manager/blob/main/docs/quickstart.md)
- [Installation](https://github.com/symfinity/font-manager/blob/main/docs/installation.md)
- [Configuration](https://github.com/symfinity/font-manager/blob/main/docs/configuration.md)
- [Export formats](https://github.com/symfinity/font-manager/blob/main/docs/exports.md)
