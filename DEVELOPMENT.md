# DEVELOPMENT.md

# WP Suspicious PHP Access Scanner - Development Guide

## Requirements

-   PHP 7.4+
-   WordPress 6.8+ / 7.x+
-   Composer 2.x
-   Git
-   Apache 2.4+

## Development Principles

-   WordPress Coding Standards (WPCS)
-   PSR-4 autoloading
-   SOLID where appropriate
-   Read-only access to server logs
-   Secure by default
-   Backward compatible with PHP 7.4

## Recommended Tools

-   Composer
-   PHP_CodeSniffer
-   WPCS
-   PHPStan
-   PHPUnit
-   GitHub Actions

## Repository Layout

``` text
assets/
languages/
templates/
src/
tests/
composer.json
phpcs.xml.dist
phpstan.neon.dist
phpunit.xml.dist
```

## Composer

``` bash
composer install
composer dump-autoload
```

## Coding Standards

Run:

``` bash
vendor/bin/phpcs
vendor/bin/phpcbf
```

Follow: - Escape all output - Sanitize all input - Verify nonces - Check
capabilities - Use translation functions - Add PHPDoc for public APIs

## Static Analysis

``` bash
vendor/bin/phpstan analyse
```

Target: Level 8 or highest practical level while supporting PHP 7.4.

## Testing

``` bash
vendor/bin/phpunit
```

Recommended coverage: - Apache parser - URI normalization - Inventory
builder - Detection rules - CSV export - Database repositories

## Git Workflow

Main branches: - main - develop

Feature branches:

``` text
feature/parser
feature/inventory
feature/detector
feature/admin-ui
```

Commit style:

``` text
feat:
fix:
docs:
refactor:
test:
chore:
```

## Release Process

1.  Update CHANGELOG.md
2.  Update plugin version
3.  Run tests
4.  Run PHPCS
5.  Run PHPStan
6.  Create Git tag
7.  Build ZIP artifact
8.  Publish release

## CI Recommendations

GitHub Actions should run: - Composer install - PHPCS - PHPStan -
PHPUnit - Build plugin ZIP

## Security Checklist

-   No shell_exec(), exec(), system(), passthru()
-   Validate log path
-   Read-only file access
-   Escape HTML output
-   Sanitize request parameters
-   Capability checks
-   Nonce verification

## Performance Guidelines

-   Use SplFileObject
-   Stream large files
-   Cache inventory
-   Cache file status
-   Aggregate duplicate requests
-   Store summaries, not raw log entries

## Documentation

Maintain: - README.md - readme.txt - ARCHITECTURE.md - DEVELOPMENT.md -
DATABASE.md - ROADMAP.md - CHANGELOG.md - SECURITY.md
