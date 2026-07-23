---
title: "Tenant Module Documentation"
type: documentation
tags: [module, documentation, multi-tenancy, architecture]
created: 2026-07-14
updated: 2026-07-14
---

# Modulo Tenant

## Overview

Il modulo **Tenant** implementa la funzionalità multi-tenancy per la piattaforma Laraxot. Fornisce l'isolamento dati tra tenant, routing tenant-aware e gestione della configurazione per ambienti multi-tenant complessi.

## Scopo

- Supporto multi-tenancy a livello architetturale
- Isolamento dati tra tenant completamente trasparente
- Routing e middleware tenant-aware
- Configurazione per-tenant isolata
- Database separation o schema separation strategies

## Funzionalità Principali

- **Tenant Isolation**: Isolamento dati completo tra tenant
- **Tenant Routing**: Routing tenant-aware per URL dinamici
- **Tenant Database**: Database isolation o schema separation
- **Tenant Configuration**: Configurazione per-tenant via environment
- **Tenant Switching**: Cambio tenant durante request lifecycle
- **Multi-Database Support**: Connessioni database multiple per tenant

## Struttura del Modulo

```
Modules/Tenant/
├── app/
│   ├── Models/
│   │   ├── Tenant.php              # Tenant model
│   │   └── TenantUser.php
│   ├── Services/
│   │   ├── TenantService.php
│   │   └── TenantSwitcher.php
│   ├── Actions/
│   │   ├── CreateTenantAction.php
│   │   └── SwitchTenantAction.php
│   ├── Filament/
│   │   └── Resources/
│   │       └── TenantResource.php
│   ├── Middleware/
│   │   └── SetTenant.php
│   └── Traits/
│       ├── BelongsToTenant.php
│       └── TenantScoped.php
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
├── resources/
│   ├── views/
│   └── lang/
├── tests/
├── docs/
│   ├── README.md
│   ├── architecture.md
│   ├── isolation-strategy.md
│   └── configuration.md
├── module.json
└── composer.json
```

## Componenti Principali

| Classe | Scopo | Extends |
|--------|-------|---------|
| `Tenant` | Modello tenant | `XotBaseModel` |
| `TenantUser` | Relazione user-tenant | `XotBaseModel` |
| `TenantService` | Logica tenant | - |
| `TenantSwitcher` | Context switching | - |
| `SetTenant` | Middleware tenant-aware | - |
| `BelongsToTenant` | Trait scope queries | - |

## Trait Disponibili

| Trait | Scopo | Utilizzo |
|-------|-------|----------|
| `BelongsToTenant` | Auto-scope alle query | Tutte i modelli |
| `TenantScoped` | Relazione tenant | Modelli multi-tenant |

**Utilizzo**:
```php
use Modules\Tenant\Traits\BelongsToTenant;

class User extends Model
{
    use BelongsToTenant;
    
    // Queries automatically scoped to current tenant
}
```

## Utilizzo Comune

### Scenario 1: Creare un Tenant

```php
use Modules\Tenant\Actions\CreateTenantAction;

$tenant = CreateTenantAction::execute([
    'name' => 'Acme Corp',
    'domain' => 'acme.example.com',
    'database' => 'acme_db', // per schema separation
]);
```

### Scenario 2: Switchare Tenant

```php
use Modules\Tenant\Services\TenantSwitcher;

TenantSwitcher::switch($tenant);

// Queries automaticamente scoped
$users = User::all(); // Only tenant's users
```

### Scenario 3: Query Tenant-Scoped

```php
$tenant = auth()->user()->tenant;

// Automatico via BelongsToTenant trait
$articles = Article::all(); // Only this tenant's articles

// Esplicito se necessario
$articles = Article::whereTenant($tenant)->get();
```

## Configuration

### Multi-Tenancy Strategy

Scegliere strategia in `laravel/config/local/tenant/config.php`:

```php
return [
    // Strategy: 'database' (separate DB per tenant)
    //           'schema' (separate schema same DB)
    //           'row' (row-level isolation)
    'strategy' => env('TENANT_STRATEGY', 'schema'),
    
    // Tenant identification
    'identifier' => env('TENANT_IDENTIFIER', 'domain'),
    
    // Database connections per tenant
    'database_prefix' => 'tenant_',
];
```

### Tenant Middleware

Registrare middleware in `ServiceProvider`:

```php
protected function registerMiddleware()
{
    $this->app['router']
        ->middlewareGroup('tenant', [
            SetTenant::class,
        ]);
}
```

## Routing

### Tenant-Aware Routes

```php
// routes/web.php
Route::middleware('tenant')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'show']);
    Route::resource('articles', ArticleController::class);
});
```

## Testing

```bash
# Run Tenant module tests
./vendor/bin/pest Modules/Tenant/tests

# Run isolation tests
./vendor/bin/pest Modules/Tenant/tests/Feature/TenantIsolationTest.php

# With coverage
./vendor/bin/pest Modules/Tenant/tests --coverage
```

## Quality Standards

- **PHPStan**: Level 10 (zero baseline)
- **Test Coverage**: Minimum 85% (isolation critical)
- **Code Style**: PSR-12 via Pint

Run locally:
```bash
php -d memory_limit=-1 ./vendor/bin/phpstan analyse --level=max Modules/Tenant
./vendor/bin/pest Modules/Tenant/tests --coverage
./vendor/bin/pint Modules/Tenant
```

## Documentation Index

- [Architecture Details](ARCHITECTURE.md) — Multi-tenancy design patterns
- [Isolation Strategy](./isolation-strategy.md) — Data isolation approaches
- [Configuration](./configuration.md) — Environment setup per tenant
- [Troubleshooting](./troubleshooting.md) — Common isolation issues
- [Testing Guide](./testing.md) — Testing multi-tenant features

## Dipendenze / Moduli Correlati

- [Xot - Framework Base](../Xot/docs/README.md) — Always dependency
- [User - Authentication](../User/docs/README.md) — For tenant users
- [Cms - Content](../Cms/docs/README.md) — Tenant content separation
- [Lang - Translations](../Lang/docs/README.md) — Per-tenant translations

## Documenti Correlati

- [Multi-Tenancy Architecture](../../../docs/wiki/standards/multi-tenancy.md)
- [Data Isolation Patterns](../../../docs/wiki/standards/data-isolation.md)
- [Tenant Security](../../../docs/wiki/standards/tenant-security.md)
- [PHPStan Configuration](../../../phpstan.neon)

## Regole Critiche

1. **Always extend Xot base classes** — Never extend Laravel/Filament directly
2. **Use namespace `Modules\Tenant`** — Never `app\Tenant`
3. **Strict typing** — `declare(strict_types=1);` in all files
4. **BelongsToTenant everywhere** — All multi-tenant models MUST use trait
5. **Never hardcode tenant** — Always use current tenant context
6. **Test isolation** — Every feature test must verify isolation
7. **No data leaks** — Audit queries for cross-tenant data exposure

## Critical Checklist

- [ ] All models have `BelongsToTenant` trait
- [ ] All Eloquent queries include tenant scope
- [ ] Tests verify no cross-tenant data access
- [ ] Middleware `SetTenant` registered on all routes
- [ ] Database strategy documented and consistent
- [ ] Configuration per-environment working

## Standard Rules & Workflow

- [[BMAD Method](../../../docs/wiki/concepts/bmad-method.md)]
- [[Context Engineering](../../../docs/wiki/concepts/context-engineering.md)]
- [[LLM Wiki Governance](../../../docs/wiki/concepts/llm-wiki-governance.md)]

---

**Status**: ✅ Production  
**Last Updated**: 2026-07-14  
**Requirements**: PHP 8.3+, Laravel 12  
**PHPStan Level**: 10 (Target)
**Security Review**: Completed 2026-Q2
