# CLI Commands Reference

## fonts:migrate-from-google-fonts

**Automatically migrate from neuralglitch/google-fonts**

```bash
# Preview changes (recommended)
php bin/console fonts:migrate-from-google-fonts --dry-run

# Apply migration
php bin/console fonts:migrate-from-google-fonts
```

**What it does:**
- Converts `google_fonts.yaml` → `font_manager.yaml`
- Updates all templates: `google_fonts()` → `font_manager()`
- Migrates manifest file
- Creates backups automatically

**Options:**
- `--dry-run` - Preview without changes
- `--skip-templates` - Skip template updates
- `--skip-config` - Skip config updates

See [Migration Guide](migration.md) for full details.

---

## fonts:search

Search for fonts by name using a provider.

```bash
# Search using default provider
php bin/console fonts:search roboto

# Search with custom provider
php bin/console fonts:search --provider=google ubuntu

# Limit results
php bin/console fonts:search --limit=10 sans
```

**Options:**
- `--provider=NAME` - Provider to use (google, bunny, local)
- `--limit=N` - Maximum results (default: 20)

**Note:** Only Google provider supports search API. Bunny and Local providers will show an error.

---

## fonts:lock

Scan templates and lock all used fonts for production.

```bash
# Scan default template directories
php bin/console fonts:lock

# Scan specific directories
php bin/console fonts:lock templates/ views/
```

**What it does:**
1. Scans Twig templates for `font_manager()` calls
2. Downloads all referenced fonts
3. Creates manifest file
4. Saves fonts to `assets/fonts/`

**Output:**
- Font files: `assets/fonts/{font-name}-{weight}-{style}.woff2`
- CSS file: `assets/fonts/{font-name}.css`
- Manifest: `var/font-manager.lock.json`

---

## fonts:status

Show status of locked fonts.

```bash
php bin/console fonts:status
```

**Shows:**
- Manifest information
- Locked fonts list
- Weights and styles per font
- Number of files
- Provider used

---

## fonts:validate

Validate local font files exist.

```bash
php bin/console fonts:validate
```

**What it does:**
1. Checks local fonts configuration
2. Verifies all referenced files exist
3. Reports missing files with paths

**Use case:** Validate custom brand fonts before deployment.

---

## fonts:prune

Remove unused locked fonts.

```bash
# Preview what would be deleted
php bin/console fonts:prune --dry-run

# Actually delete unused fonts
php bin/console fonts:prune
```

**What it does:**
1. Compares manifest to actual files
2. Identifies unused files
3. Optionally removes them

---

## Typical Workflows

### Development Workflow

```bash
# 1. Search for fonts
php bin/console fonts:search inter

# 2. Add to template
# {{ font_manager('Inter', '400 700') }}

# 3. Test in browser
# (fonts load from CDN)
```

### Production Deployment

```bash
# 1. Lock fonts before deployment
php bin/console fonts:lock

# 2. Check status
php bin/console fonts:status

# 3. Compile assets
php bin/console asset-map:compile

# 4. Deploy
```

### Maintenance

```bash
# Remove old/unused fonts
php bin/console fonts:prune

# Validate local fonts
php bin/console fonts:validate
```

---

For more information:
- [Usage Guide](usage.md)
- [Providers](providers.md)
- [Configuration](configuration.md)

