# _bak — activity_log duplicate / anti-pattern (non eseguire)

<<<<<<< HEAD
[![Module](https://img.shields.io/badge/Module-_bak — activity_log duplicate / anti-pattern (non eseguire)-8B0000.svg)]()
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

**Modulo** `Activity` · **Laraxot** · **FixCity Platform** · PHPStan 10 · Filament 5
=======
Laravel non carica sottocartelle di `migrations/`.

## Vietato (consolidati nel file owner)

| File errato | Errore |
|-------------|--------|
| `2024_10_10_000000_add_attribute_changes_to_activity_table.php` | prefisso `add_*` — usare `tableUpdate` nel `create_*` |
| `2026_02_13_171410_fix_causer_id_to_uuid.php` | prefisso `fix_*` — stesso |
| `2026_07_01_000000_update_activity_log_schema.php` | `extends Migration` + nome `update_*` |
| vecchi `2023_*` / `2024_01_01_*_create_activity_table` | secondi `create_*` sullo stesso modello |

## Canon

`../2026_06_10_141000_create_activity_table.php` — `XotBaseMigration`, model `Activity`.
>>>>>>> 13cf1831 (.)
