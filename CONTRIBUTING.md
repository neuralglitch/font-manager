# Contributing to Font Manager

Thank you for your interest in contributing to the Font Manager bundle for Symfony!

## Development Setup

1. **Clone the repository**:
   ```bash
   git clone https://github.com/neuralglitch/font-manager.git
   cd font-manager
   ```

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Run quality checks**:
   ```bash
   # Quick QA check
   make qa
   
   # Or individually:
   make test        # PHPUnit tests
   make phpstan     # Static analysis (PHPStan)
   make psalm       # Static analysis (Psalm)
   make cs-check    # Code style check
   ```

4. **Run full QA suite**:
   ```bash
   make qa-full     # Includes mutation testing
   ```

## Quality Assurance Tools

This project uses multiple QA tools to ensure code quality:

- **PHPStan** (level max) - Static analysis
- **Psalm** (level 4) - Additional static analysis
- **PHP-CS-Fixer** - PSR-12 code style
- **PHPUnit** - Unit and integration tests (>89% coverage)
- **Infection** - Mutation testing (MSI ≥40%)
- **PHPMetrics** - Code quality metrics
- **Rector** - Automated refactoring suggestions
- **Renovate** - Automated dependency updates

All tools can be run via `make` commands (see `make help`).

## Making Changes

1. **Create a feature branch**:
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Write code** following the [Coding Standards](#coding-standards)

3. **Write tests** for new functionality (aim for >90% coverage)

4. **Update documentation** if needed

5. **Run QA checks**:
   ```bash
   make qa
   ```

6. **Commit your changes** following [Conventional Commits](#commit-messages)

## Commit Messages

Follow [Conventional Commits](https://www.conventionalcommits.org/):

```
feat: add Bunny Fonts provider support
fix: resolve manifest parsing issue
docs: update provider comparison
test: add tests for FontDownloader service
refactor: optimize manifest file reading
```

**Examples:**
- `feat(provider): add Fontsource CDN support`
- `fix(command): resolve font lock race condition`
- `docs(readme): update migration guide`
- `test(service): add edge cases for font variants`
- `chore(deps): update symfony to 7.2`

## Coding Standards

### PHP

This project follows **Symfony Coding Standards** with strict typing:

✅ PHP 8.1+ features (enums, readonly properties, constructor promotion)  
✅ `declare(strict_types=1);` in all files  
✅ Final classes by default  
✅ Full type hints (parameters, returns, properties)  
✅ PHPStan level max compliance  
✅ Psalm level 4 compliance

#### File Structure

```php
<?php

declare(strict_types=1);

namespace NeuralGlitch\FontManager\Service;

final class ServiceName
{
    public function __construct(
        private readonly Dependency $dependency
    ) {}
    
    public function methodName(): ReturnType
    {
        // Implementation
    }
}
```

### Naming Conventions

- **Classes/Interfaces**: `PascalCase`
- **Methods/Properties**: `camelCase`
- **Constants**: `UPPER_SNAKE_CASE`
- **Enums**: `PascalCase` (class), `UPPER_CASE` (cases)
- **Twig functions**: `snake_case`
- **File names**: Match class name

### Configuration

- YAML format for configuration files
- Snake_case for parameter keys
- Provide comprehensive defaults
- Document all options

### Error Handling

Use specific exceptions:
- `FontManagerException` (base)
- `FontDownloadException`
- `ManifestException`
- `ProviderException`

Provide meaningful error messages and preserve exception chains.

## Pull Request Process

### Before Submitting

1. **Run full QA suite**:
   ```bash
   make qa-full
   ```

2. **Update documentation**:
   - README.md (if public API changes)
   - CHANGELOG.md (add entry under Unreleased)
   - Code comments/PHPDoc
   - docs/ files if needed

3. **Ensure tests pass**:
   - All existing tests pass
   - New tests added for new features
   - Coverage remains >89%

### Submitting PR

1. **Push your branch**:
   ```bash
   git push origin feature/your-feature-name
   ```

2. **Create Pull Request on GitHub** with:
   - Descriptive title
   - Reference to related issues (#123)
   - Clear description of changes
   - Breaking changes documented
   - Screenshots/examples if relevant

3. **PR Template**:

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix (non-breaking)
- [ ] New feature (non-breaking)
- [ ] Breaking change
- [ ] Documentation update
- [ ] Dependency update

## Related Issue
Fixes #(issue number)

## Testing
- [ ] Tests added/updated
- [ ] All tests pass (`make test`)
- [ ] PHPStan passes (`make phpstan`)
- [ ] Psalm passes (`make psalm`)
- [ ] Code style passes (`make cs-check`)
- [ ] Manual testing completed

## Checklist
- [ ] Code follows project style
- [ ] Documentation updated
- [ ] CHANGELOG.md updated
- [ ] No breaking changes (or documented)
- [ ] Coverage remains >89%
```

### Review Process

- Maintainers will review your PR
- Address feedback and update PR
- CI must pass (all QA checks)
- Once approved, maintainer will merge

## Reporting Issues

### Bug Reports

Include:

- Symfony version
- PHP version
- Font Manager version
- Provider used (google/bunny/fontsource/local)
- Steps to reproduce
- Expected vs actual behavior
- Error messages/stack traces
- Minimal code example

Use the GitHub issue template for bug reports.

### Feature Requests

Include:

- Clear description of feature
- Use cases and benefits
- Possible implementation approach
- Examples from other projects (if any)
- Impact on existing functionality

### Security Issues

**Do NOT** open public issues for security vulnerabilities.

Email security concerns to: **security@neuralglitch.com**

## Testing Guidelines

### Unit Tests

✅ Test all public methods  
✅ Test error conditions  
✅ Use descriptive test method names  
✅ Follow Arrange-Act-Assert pattern  
✅ Mock external dependencies  
✅ Aim for >90% coverage

**Example:**

```php
public function testDownloadFontThrowsExceptionOnInvalidUrl(): void
{
    $downloader = new FontDownloader(...);
    
    $this->expectException(FontDownloadException::class);
    $this->expectExceptionMessage('Invalid font URL');
    
    $downloader->downloadFont('Invalid Font', [400], ['normal']);
}
```

### Integration Tests

✅ Test command execution  
✅ Test Twig function rendering  
✅ Test file system operations  
✅ Test provider integrations

## Dependency Management

### Renovate

This project uses [Renovate](https://docs.renovatebot.com/) for automated dependency updates:

- **Schedule**: Weekly (Mondays before 6am CET)
- **Grouped updates**: Symfony packages, QA tools
- **Auto-merge**: Minor/patch updates for safe dependencies
- **Security**: Updates at any time

Renovate will:
1. Create a "Dependency Dashboard" issue
2. Open PRs for dependency updates
3. Auto-merge safe updates
4. Alert on security vulnerabilities

**No manual dependency updates needed!** Renovate handles it.

### Manual Dependency Updates

If you need to update a dependency manually:

```bash
composer update vendor/package --with-dependencies
make qa-full  # Ensure everything still works
```

Then create a PR with `chore(deps): update vendor/package` commit.

## Code Review Checklist

Before submitting, ensure:

- [ ] PHPStan level max passes (`make phpstan`)
- [ ] Psalm level 4 passes (`make psalm`)
- [ ] All tests pass (`make test`)
- [ ] Code coverage >89%
- [ ] Code style compliant (`make cs-check`)
- [ ] New functionality has tests
- [ ] Documentation updated
- [ ] CHANGELOG.md updated
- [ ] No hardcoded values
- [ ] Proper error handling
- [ ] Type hints on all methods/properties
- [ ] No unused imports
- [ ] Meaningful variable/method names
- [ ] Commit messages follow convention

## Development Tools

### Make Commands

```bash
make help         # Show all available commands
make test         # Run PHPUnit tests
make test-coverage # Run tests with coverage
make phpstan      # Run PHPStan
make psalm        # Run Psalm
make infection    # Run mutation testing
make metrics      # Generate code metrics
make rector       # Check for refactorings
make cs-fix       # Fix code style
make cs-check     # Check code style
make qa           # Quick QA (CS + PHPStan + Psalm + Tests)
make qa-full      # Full QA suite
```

### Composer Scripts

All make commands use composer scripts internally:

```bash
composer test
composer test:coverage
composer phpstan
composer psalm
composer infection
composer cs-fix
composer qa
composer qa-full
```

## Release Process

(For maintainers)

1. Update CHANGELOG.md (move Unreleased to version)
2. Update version in README.md (if needed)
3. Tag release: `git tag v1.0.0`
4. Push tag: `git push origin v1.0.0`
5. GitHub Actions will create release
6. Packagist will auto-update

## Getting Help

- **GitHub Issues**: Bug reports and feature requests
- **GitHub Discussions**: Questions and general discussion
- **Documentation**: Check `/docs` directory
- **Examples**: See `/docs/usage.md`

---

Thank you for contributing! 🎉

