# Migration from google-fonts Bundle

If you're migrating from `neuralglitch/google-fonts`, this guide will help you transition smoothly.

## Automatic Migration (Recommended)

The easiest way to migrate is using the built-in migration command:

```bash
# Preview changes
php bin/console fonts:migrate-from-google-fonts --dry-run

# Apply migration
php bin/console fonts:migrate-from-google-fonts
```

**What it does:**

✅ Converts `google_fonts.yaml` → `font_manager.yaml`  
✅ Updates all templates: `google_fonts()` → `font_manager()`  
✅ Migrates manifest: `google-fonts.lock.json` → `font-manager.lock.json`  
✅ Creates backups automatically  
✅ Shows summary of all changes

**Options:**
```bash
# Dry run (preview only)
php bin/console fonts:migrate-from-google-fonts --dry-run

# Skip template migration
php bin/console fonts:migrate-from-google-fonts --skip-templates

# Skip config migration
php bin/console fonts:migrate-from-google-fonts --skip-config
```

After migration, test your application and then:
```bash
composer remove neuralglitch/google-fonts
```

## Manual Migration

If you prefer manual migration or need more control:

### 1. Update Composer

```bash
composer remove neuralglitch/google-fonts
composer require neuralglitch/font-manager
```

### 2. Update Configuration

**Before:**
```yaml
# config/packages/google_fonts.yaml
google_fonts:
    lock_fonts: false
    fonts_dir: '%kernel.project_dir%/assets/fonts'
    manifest_path: '%kernel.project_dir%/var/google-fonts.lock.json'
    use_locked_fonts: false
```

**After:**
```yaml
# config/packages/font_manager.yaml
font_manager:
    default_provider: 'google'  # Keep using Google Fonts
    lock_fonts: false
    fonts_dir: '%kernel.project_dir%/assets/fonts'
    manifest_path: '%kernel.project_dir%/var/font-manager.lock.json'
    use_locked_fonts: false
```

### 3. Update Templates

**Before:**
```twig
{{ google_fonts('Roboto', '400 700', 'normal') }}
{{ google_fonts('Inter', '400 600', 'normal italic', 'swap', true) }}
```

**After:**
```twig
{{ font_manager('Roboto', '400 700', 'normal') }}
{{ font_manager('Inter', '400 600', 'normal italic', 'swap', true) }}
```

### 4. Update Commands

| google-fonts | font-manager |
|--------------|--------------|
| `gfonts:search` | `fonts:search --provider=google` |
| `gfonts:import` | `fonts:search --provider=google` |
| `gfonts:lock` | `fonts:lock` |
| `gfonts:status` | `fonts:status` |
| `gfonts:prune` | `fonts:prune` |
| `gfonts:validate` | `fonts:validate` |
| `gfonts:warmup-cache` | *(removed - automatic caching)* |

## Advantages of Migration

### 1. Multiple Provider Support

**google-fonts:**
- Only Google Fonts

**font-manager:**
- Google Fonts
- Bunny Fonts (GDPR-compliant)
- Fontsource (version-controlled)
- Local Fonts (custom fonts)

### 2. Better Privacy

Switch to Bunny Fonts for GDPR compliance:

```yaml
font_manager:
    default_provider: 'bunny'  # Same fonts, zero tracking
```

### 3. Custom Fonts

```yaml
font_manager:
    providers:
        local:
            enabled: true
            fonts:
                BrandFont:
                    weights: [400, 700]
                    files:
                        400-normal: 'brand-regular.woff2'
```

### 4. Per-Font Provider Selection

```twig
{# Use different providers per font #}
{{ font_manager('Roboto', '400', 'normal', 'swap', false, 'bunny') }}
{{ font_manager('BrandFont', '400 700', 'normal', 'swap', false, 'local') }}
```

## Breaking Changes

### Twig Function Name

- **OLD:** `google_fonts()`
- **NEW:** `font_manager()`

### Command Prefix

- **OLD:** `gfonts:`
- **NEW:** `fonts:`

### Configuration File

- **OLD:** `config/packages/google_fonts.yaml`
- **NEW:** `config/packages/font_manager.yaml`

### Manifest File

- **OLD:** `var/google-fonts.lock.json`
- **NEW:** `var/font-manager.lock.json`

## Step-by-Step Migration

### Step 1: Install font-manager

```bash
composer require neuralglitch/font-manager
```

Keep google-fonts installed temporarily.

### Step 2: Create New Configuration

```bash
cp config/packages/google_fonts.yaml config/packages/font_manager.yaml
```

Edit the new file to use `font_manager:` root key.

### Step 3: Update Templates Gradually

Update one template at a time:

```twig
{# OLD #}
{{ google_fonts('Roboto', '400 700') }}

{# NEW #}
{{ font_manager('Roboto', '400 700', 'normal', 'swap', false, 'google') }}
```

Test each template to ensure fonts load correctly.

### Step 4: Update CI/CD

If you run `gfonts:lock` in your deployment:

```bash
# OLD
php bin/console gfonts:lock

# NEW
php bin/console fonts:lock
```

### Step 5: Remove google-fonts

Once all templates are updated and tested:

```bash
composer remove neuralglitch/google-fonts
rm config/packages/google_fonts.yaml
```

## Troubleshooting

### Fonts not loading after migration

**Check configuration:**
```bash
php bin/console debug:config font_manager
```

**Check provider:**
```yaml
font_manager:
    default_provider: 'google'  # Explicitly set to google
```

### Search command not working

The search command requires explicit provider:

```bash
# Won't work:
php bin/console fonts:search roboto

# Will work:
php bin/console fonts:search roboto --provider=google
```

Or set Google as default provider in config.

### Locked fonts from old bundle

Delete old manifest and re-lock:

```bash
rm var/google-fonts.lock.json
php bin/console fonts:lock
```

## Recommended: Switch to Bunny Fonts

After migration, consider switching to Bunny Fonts for better privacy:

```yaml
# config/packages/font_manager.yaml
font_manager:
    default_provider: 'bunny'  # Same fonts, better privacy
```

Templates automatically work - no changes needed!

## Need Help?

- [Full Documentation](../README.md#documentation)
- [Export Formats](exports.md)
- [GitHub Issues](https://github.com/neuralglitch/font-manager/issues)
- [Provider Guide](providers.md)

