<<<<<<< HEAD
=======
<<<<<<< HEAD
# Xot
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
---
title: "Readme"
type: reference
tags: [wiki, no-frontmatter-fix]
created: 2026-08-24
updated: 2026-08-24
---
<<<<<<< HEAD

# Best Practices

## Laraxot Framework Standards

### Models
- ALWAYS extend module's BaseModel
- NEVER extend Eloquent\Model directly
- Use `declare(strict_types=1);` in all files
- Implement `casts()` method, not `$casts` property

### Filament Resources
- ALWAYS extend XotBaseResource
- NEVER use `->label()` method
- Return associative arrays from `getFormSchema()`
- Use enum classes instead of hardcoded options

### Migrations
- Use anonymous classes extending XotBaseMigration
- NEVER implement `down()` method
- Always check existence with `hasTable()` and `hasColumn()`
- Copy original migration with new timestamp for column additions

### Translations
- Use expanded structure ALWAYS
- NEVER remove existing keys
- Maintain consistency across all languages (IT/EN/DE)
- Use snake_case for all keys

## Code Quality
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- PHPStan level 9+ for all new code
- Complete PHPDoc annotations
- Use Safe library for unsafe functions
- Follow PSR-12 coding standards

## Documentation
- All files in docs/ must be lowercase (except README.md)
- Create bidirectional links between related documents
- Update both module and root documentation
- Include practical examples in all guides

---

=======
>>>>>>> 28b0298a (fix: phpstan issues)

[![Module](https://img.shields.io/badge/Module-Xot-8B0000.svg)]()
[![Laravel](https://img.shields.io/badge/Laravel-13-red?style=for-the-badge)](https://laravel.com/)](https://laravel.com/)
[![Filament](https://img.shields.io/badge/Filament-5-ffab00?style=for-the-badge)](https://filamentphp.com/)](https://filamentphp.com/)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge)](https://php.net/)](https://php.net/)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge)](https://php.net/)](https://phpstan.org/)
[![PSR-12](https://img.shields.io/badge/Code-PSR--12-blue?style=for-the-badge)](https://www.php-fig.org/psr/psr-12/)](https://www.php-fig.org/psr/psr-12/)
[![Architecture](https://img.shields.io/badge/Architecture-Modular-purple?style=for-the-badge)](https://martinfowler.com/articles/paradigm-shifts.html)]()
]()

> **Core module for the FixCity Platform.**

## Perché esiste

Core module for the FixCity Platform.

## Superpoteri

- Modular component with XotBase patterns
- Professional-grade implementation
- Integrated with FixCity Platform

## Documentazione

| Lingua | Link |
|--------|------|
| 🇮🇹 Presentazione | Questo file (`README.md`) |
| 🇬🇧 Business card | [docs/readme-en.md](./docs/readme-en.md) |
| 📚 Wiki tecnica | [./docs/wiki/](./docs/) |

---

<<<<<<< HEAD
**Modulo** `Xot` · **Laraxot** · **FixCity Platform** · PHPStan 10 · Filament 5
=======
>>>>>>> laraxot/dev
<!-- Merged from readme.md, which collided with this file on case-insensitive filesystems. -->

---
module: theme
topic: readme
canonical: ../../../../Themes/docs/shared-components/README-Modules.md
---

See canonical documentation: ../../../../Themes/docs/shared-components/README-Modules.md
<<<<<<< HEAD
=======
=======
# Xot

[![Module](https://img.shields.io/badge/Module-Xot-8B0000.svg)]()
[![Laravel](https://img.shields.io/badge/Laravel-13-red?style=for-the-badge)](https://laravel.com/)](https://laravel.com/)
[![Filament](https://img.shields.io/badge/Filament-5-ffab00?style=for-the-badge)](https://filamentphp.com/)](https://filamentphp.com/)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge)](https://php.net/)](https://php.net/)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge)](https://php.net/)](https://phpstan.org/)
[![PSR-12](https://img.shields.io/badge/Code-PSR--12-blue?style=for-the-badge)](https://www.php-fig.org/psr/psr-12/)](https://www.php-fig.org/psr/psr-12/)
[![Architecture](https://img.shields.io/badge/Architecture-Modular-purple?style=for-the-badge)](https://martinfowler.com/articles/paradigm-shifts.html)]()
]()

> **Core module for the FixCity Platform.**

## Perché esiste

Core module for the FixCity Platform.

## Superpoteri

- Modular component with XotBase patterns
- Professional-grade implementation
- Integrated with FixCity Platform

## Documentazione

| Lingua | Link |
|--------|------|
| 🇮🇹 Presentazione | Questo file (`README.md`) |
| 🇬🇧 Business card | [docs/readme-en.md](./docs/readme-en.md) |
| 📚 Wiki tecnica | [./docs/wiki/](./docs/) |

---

**Modulo** `Xot` · **Laraxot** · **FixCity Platform** · PHPStan 10 · Filament 5
>>>>>>> 7f6cf6be (.)
>>>>>>> 28b0298a (fix: phpstan issues)
>>>>>>> laraxot/dev
