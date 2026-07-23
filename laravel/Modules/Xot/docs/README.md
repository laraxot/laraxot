---
title: "Xot Module - Updated Documentation (Clean)"
type: documentation
tags: [module, documentation, framework, template]
created: 2026-07-14
updated: 2026-07-14
---

# 🏗️ Xot Module - Il Cuore del Framework Laraxot

[![Laravel 12.x](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com/)
[![Filament 5.x](https://img.shields.io/badge/Filament-5.x-blue.svg)](https://filamentphp.com/)
[![PHP 8.4](https://img.shields.io/badge/PHP-8.4-blueviolet.svg)](https://www.php.net/)
[![PHPStan Level 10](https://img.shields.io/badge/PHPStan-Level%2010-brightgreen.svg)](https://phpstan.org/)
[![Modular Architecture](https://img.shields.io/badge/Architecture-Modular%20Monolith-yellow.svg)](https://martinfowler.com/articles/modular-monolith.html)

> **🚀 Xot Module**: Framework base e cuore architetturale di Laraxot - fornisce classi base, traits, convenzioni e infrastruttura core per tutti i moduli dell'ecosistema.

## 📋 Overview

Il modulo **Xot** è il **framework base** di Laraxot, un ecosistema modulare basato su **Laravel 12** e **Filament 5**, progettato per applicazioni enterprise. Fornisce gli strumenti fondamentali e i pattern architetturali per garantire coerenza, estensibilità e manutenibilità in tutto il progetto.

### Principi Fondamentali

- **Modularità**: Ogni funzionalità è organizzata in moduli indipendenti e autoconsistenti
- **Coerenza**: Adozione di una struttura uniforme, convenzioni di naming e best practice standardizzate
- **Estensibilità**: Progettato per facilitare l'aggiunta di nuovi moduli e l'espansione delle funzionalità esistenti
- **Manutenibilità**: Codice pulito, ben documentato e supportato da strumenti di analisi statica

## 🏗️ Module Directory Structure Standard

To ensure consistent autoloading and architectural integrity, all modules must follow this structure:

```
Modules/ModuleName/
├── app/                              # All PHP code (PSR-4 mapped)
│   ├── Actions/                      # Reusable action classes
│   ├── Models/                       # Eloquent models
│   ├── Services/                     # Business logic services
│   ├── Filament/
│   │   ├── Resources/
│   │   ├── Pages/
│   │   └── Widgets/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Requests/
│   ├── Traits/                       # Reusable traits
│   ├── Enums/
│   └── Events/
├── database/                         # Lowercase only (CRITICAL)
│   ├── migrations/
│   ├── factories/
│   └── seeders/
├── resources/
│   ├── views/
│   └── lang/
├── tests/
│   ├── Unit/
│   └── Feature/
├── docs/                             # Documentation
│   └── README.md
├── module.json                       # Module metadata
└── composer.json                     # Module dependencies
```

**FORBIDDEN**: Capitalized directories at root (e.g., `Actions/`, `Database/`). All code must be in `app/`.

## ⚡ Core Architecture

### Base Classes Pattern

Tutti i componenti principali dei moduli devono estendere le classi base fornite da Xot per ereditare funzionalità comuni e garantire coerenza.

```php
// Xot Base Classes (sempre usare)
use Modules\Xot\Models\XotBaseModel;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Modules\Xot\Services\XotBaseService;
use Modules\Xot\Actions\XotBaseAction;
use Modules\Xot\Providers\XotBaseServiceProvider;
```

**Example**: Resource Filament
```php
use Modules\Xot\Filament\Resources\XotBaseResource;

class UserResource extends XotBaseResource
{
    protected static ?string $model = User::class;
    // table() and form() inherited from base
}
```

### Traits Ecosystem

Xot fornisce un ricco ecosistema di Trait per aggiungere funzionalità comuni ai modelli e ad altre classi:

| Trait | Utilizzo | Scopo |
|-------|----------|-------|
| `HasXotTable` | Modelli | Aggiunge funzionalità avanzate alle tabelle Filament |
| `HasUuid` | Modelli | Gestisce automaticamente UUID come chiavi primarie |
| `HasMedia` | Modelli | Integra Spatie Media Library con convenzioni standard |
| `HasStates` | Modelli | Fornisce gestione degli stati per i modelli |
| `TransTrait` | Modelli | Semplifica le traduzioni dinamiche |
| `InteractsWithForms` | Widget | Gestione form nei widget Filament |

### Service Provider Pattern

I Service Provider di ogni modulo estendono `XotBaseServiceProvider`, che automatizza la registrazione di:

- Migrations, Views, Translations, Config
- Routes (web.php, api.php)
- Filament Resources, Pages, Widgets
- Artisan Commands e Policies

```php
use Modules\Xot\Providers\XotBaseServiceProvider;

class MyModuleServiceProvider extends XotBaseServiceProvider
{
    // Automatically registers migrations, views, routes, etc.
}
```

## 🎯 Core Features

### Actions Framework

Un pattern standardizzato per incapsulare la business logic in classi riutilizzabili e testabili.

```php
use Modules\Xot\Actions\XotBaseAction;

class CreateUserAction extends XotBaseAction
{
    public function execute(array $data): User
    {
        $user = User::create($data);
        event(new UserCreated($user));
        return $user;
    }
}
```

### Enums System

Le Enum di Xot implementano `XotBaseEnum`, che fornisce traduzioni automatiche:

```php
use Modules\Xot\Enums\XotBaseEnum;

enum UserStatus: string implements XotBaseEnum
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public function getLabel(): string
    {
        return __('xot::enums.user_status.'.$this->value);
    }
}
```

### Filament Integration

Xot fornisce wrapper base per tutti i componenti Filament:
- `XotBaseResource`
- `XotBaseWidget`
- `XotBaseWizardWidget`
- `XotBasePage`
- `XotBaseAction`

**Rule**: Never extend Filament classes directly. Always use Xot wrappers.

## 🛠️ Development & Quality

### PHPStan Level 10 Compliance

Xot ha raggiunto la piena conformità PHPStan Level 10 senza compromessi:

- ✅ Zero baseline entries
- ✅ Nessuna modifica a phpstan.neon
- ✅ Solo correzioni reali del codice
- ✅ Type safety al 100%

**Analizza con memoria illimitata**:
```bash
php -d memory_limit=-1 ./vendor/bin/phpstan analyse Modules/ --level=max
```

### Quality Standards

| Tool | Standard | Config |
|------|----------|--------|
| **PHPStan** | Level 10 | `laravel/phpstan.neon` |
| **Pest** | Tests in `tests/` | `phpunit.xml` |
| **Pint** | PSR-12 + Laraxot | `.pint.json` |
| **Coverage** | Minimum 80% | Via Pest |

### Convenzioni

- **Namespace**: `Modules\{ModuleName}` (NO `app` segment)
- **Tipizzazione Forte**: `declare(strict_types=1);` in all files
- **Traduzioni**: Structured format `['label' => '...', 'tooltip' => '...']`
- **Migrations**: Anonymous classes only

### Run Quality Gate

```bash
# From project root (laravel/)

# PHPStan
php -d memory_limit=-1 ./vendor/bin/phpstan analyse --level=max

# Pest
./vendor/bin/pest

# Pint
./vendor/bin/pint
```

## 📚 Architecture Patterns

### Module Dependency Graph

```
Xot (foundation)
  ├── User (authentication, authorization)
  ├── Lang (translations)
  ├── Cms (content management)
  ├── Tenant (multi-tenancy)
  ├── Notify (notifications)
  ├── Media (file management)
  ├── Geo (geolocation)
  ├── Activity (activity logging)
  ├── Job (job management)
  └── [Other modules]
```

All modules depend on **Xot**. Never have circular dependencies.

### Key Design Decisions

1. **Service Provider Automation**: Xot's `XotBaseServiceProvider` auto-registers all module components
2. **Trait-Based Composition**: Prefer traits over inheritance for cross-cutting concerns
3. **Enum Internationalization**: Enums handle their own translations
4. **Action Classes**: Business logic encapsulated in reusable action classes
5. **No Log Statements**: Let Laravel's exception handler manage logging

## 🔗 Related Documentation

- [Module Documentation Pattern](../../../docs/wiki/rules/module-documentation-pattern.md)
- [Architecture Rules](../../../docs/wiki/rules/)
- [PHPStan Configuration](../../../phpstan.neon)
- [Testing Guidelines](../../../docs/wiki/standards/)

### Moduli Dipendenti

- [User Module](../User/docs/README.md) - Authentication & Authorization
- [Cms Module](../Cms/docs/README.md) - Content Management
- [Tenant Module](../Tenant/docs/README.md) - Multi-tenancy
- [Lang Module](../Lang/docs/README.md) - Translations
- [Notify Module](../Notify/docs/README.md) - Notifications

## 🗺️ Roadmap

1. **✅ Consolidamento Documentazione**: Unificare e semplificare la documentazione di tutti i moduli
2. **📋 Automazione Script di Merge**: Creare script per la gestione automatica dei conflitti comuni
3. **📈 Aumento Test Coverage**: Portare la copertura dei test per i moduli core sopra il 90%
4. **📊 Dashboard Health Check**: Introdurre una dashboard per monitorare lo stato di salute di tutti i moduli

## 🔗 Useful Links

- [CHANGELOG](./CHANGELOG.md)
- [Git Conflict Resolution Guide](../../../bashscripts/docs/git-conflict-resolution-guide.md)
- [Namespace Conventions](./namespace-conventions.md)
- [Testing Best Practices](./testing.md)

---

## Standard Rules & Workflow

- [[BMAD Method](../../../docs/wiki/concepts/bmad-method.md)]
- [[Context Engineering](../../../docs/wiki/concepts/context-engineering.md)]
- [[LLM Wiki Governance](../../../docs/wiki/concepts/llm-wiki-governance.md)]

---

**Status**: ✅ Production  
**Last Updated**: 2026-07-14  
**Maintained by**: Laraxot Core Team  
**PHPStan Level**: 10 (Compliant)
