# Migration from neuralglitch/google-fonts

There is **no** `symfinity/google-fonts` package. **`symfinity/font-manager`** is the sole Symfinity successor.

## Automated migration

If the app still uses `neuralglitch/google-fonts`, install font-manager and run:

```bash
composer remove neuralglitch/google-fonts
composer require symfinity/font-manager
php bin/console fonts:migrate-from-google-fonts --dry-run
php bin/console fonts:migrate-from-google-fonts
```

The command rewrites `config/packages/google_fonts.yaml` → `font_manager:` semantics and updates Twig usage where applicable.

**Options:**

```bash
php bin/console fonts:migrate-from-google-fonts --skip-templates
php bin/console fonts:migrate-from-google-fonts --skip-config
```

## Manual mapping

| Legacy (`google-fonts`) | Symfinity (`font-manager`) |
|-------------------------|----------------------------|
| `google_fonts:` config root | `font_manager:` |
| `google_fonts()` Twig helper | `font_manager()` |
| `gfonts:*` commands | `fonts:*` (e.g. `fonts:lock`, `fonts:search --provider=google`) |
| `var/google-fonts.lock.json` | `var/font-manager.lock.json` |

## Package identity

| Item | Legacy | Symfinity |
|------|--------|-----------|
| Composer name | `neuralglitch/google-fonts` | `symfinity/font-manager` |
| Packagist `replacement=` | — | `symfinity/font-manager` |

## Further reading

- [Migration from neuralglitch/font-manager](migration.md)
- [Successor handbook](https://github.com/symfinity/font-manager/tree/main/docs)
