# 🎨 UI

[![Stars](https://img.shields.io/github/stars/laraxot/module_ui_fila5?style=plastic&color=yellow)]()
[![Forks](https://img.shields.io/github/forks/laraxot/module_ui_fila5?style=plastic&color=green)]()
[![Issues](https://img.shields.io/github/issues/laraxot/module_ui_fila5?style=plastic&color=red)]()
[![License](https://img.shields.io/github/license/laraxot/module_ui_fila5?style=plastic&color=blue)]()
[![Last Commit](https://img.shields.io/github/last-commit/laraxot/module_ui_fila5?style=plastic&color=purple)]()
[![Release](https://img.shields.io/github/v/release/laraxot/module_ui_fila5?style=plastic&color=orange&display_name=release)]()
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge)](https://php.net/)
[![Filament](https://img.shields.io/badge/Filament-5-ffab00?style=for-the-badge)](https://filamentphp.com/)
[![Laravel](https://img.shields.io/badge/Laravel-13-red?style=for-the-badge)](https://laravel.com/)
[![Architecture](https://img.shields.io/badge/Architecture-Modular-purple?style=plastic)]()

> **Componenti UI riutilizzabili e design system**  
> Componenti UI, layout, temi e pattern di design per Filament v5.

## 🎯 La Visione

Crediamo che il software debba essere **chiaro, modulare e potente**. Ogni modulo è stato pensato per risolvere problemi reali con soluzioni eleganti.

## Perché esiste questo modulo?

**Componenti UI, layout, temi e pattern di design per Filament v5.**

In un mondo dove la complessità è l'avere, abbiamo scritto codice semplice. Questo modulo non è solo una libreria: è una **promessa di qualità** mantenuta.

## 🧘 I Principi Zen (e la nostra filosofia)

1. **Semplicità vince sulla complessità** - Il codice chiaro è più potente di mille righe di commenti.
2. **Modulare è dare vita** - Ogni pezzo può vivere da solo, ma insieme diventa un universo.
3. **Documentare è onniscienza** - La mancanza di documentazione è la paura del futuro.
4. **Testare è fidarsi** - Non fidarsi del proprio codice è fidarsi del caos.
5. **Rifattorizzare è crescere** - Lentamente, incrementalmente, diventiamo migliori.

## 💎 Le sue Superpoteri

- **Architettura modulare** - Separazione netta tra logica di business e presentazione
- **PHPStan Level 10** - Massima sicurezza tipizzazione
- **PSR-12** - Codice che parla lo stesso linguaggio del mondo
- **Filament 5** - Admin panel d'eccellenza
- **XotBase** - Pattern consolidati che funzionano

## 🚀 Quick Start

```bash
# Installazione modulo
php artisan module:enable UI
php artisan migrate

# Sviluppo locale
cd laravel
composer dev
./vendor/bin/pest Modules/UI/tests
./vendor/bin/phpstan analyse Modules/UI --memory-limit=-1
```

Configuration is in `config/ui.php`. Adjust as needed.

## 🤝 Contributing

```bash
cd laravel
composer dev
./vendor/bin/pest Modules/UI/tests
./vendor/bin/phpstan analyse Modules/UI --memory-limit=-1
```

**Before submitting:**
- [ ] Tests pass
- [ ] PHPStan L10 passes
- [ ] Code style (Pint) applied
- [ ] Documentation updated

See [ARCHITECTURE.md](./ARCHITECTURE.md) for design decisions.

## 📖 Documentazione

| Lingua | Link |
|--------|------|
| 🇮🇹 Presentazione | Questo file (`README.md`) |
| 🇬🇧 Business card | [docs/readme-en.md](./docs/readme-en.md) |
| 📚 Wiki tecnica | [./docs/wiki/](./docs/) |
| 🎯 Esempi | [docs/examples/](./docs/examples/) |
| 🏗️ Architettura | [ARCHITECTURE.md](./ARCHITECTURE.md) |
| 🧪 Testing | [TESTING.md](./TESTING.md) |
| 📜 Changelog | [CHANGELOG.md](./CHANGELOG.md) |

## 🔧 Tecnologie chiave

**Stack principale:** Laravel 13, Filament 5, XotBase

**Keywords:** UI, Components, Design System

---

**Modulo** `UI` · **Laraxot** · PHPStan 10 · Filament 5