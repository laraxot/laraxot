---
title: Tenant
module: tenant
related: Xot, User
status: production
last_updated: 2026-07-28
---

# Tenant Module

**Module**: `tenant`
**Namespace**: `Modules\Tenant\`
**Status**: ✅ Production  
**Last updated**: 2026-07-28

---

## Overview

Il modulo Tenant gestisce la multi-tenancy dell'applicazione in modo completo e robusto. Ogni tenant ha il proprio dominio (o sottodominio), le proprie configurazioni e i propri dati completamente isolati. L'isolamento avviene a livello di connessione database: ogni modulo usa automaticamente la connessione corretta basandosi sul namespace del modello.

### Key Features

- **Tenant Isolation**: Complete data separation per tenant via database/schema/scoping strategies
- **Multi-Domain Support**: Ogni tenant può essere raggiunto da più domini/sottodomini
- **Configuration Management**: Settings per-tenant con override di configurazione globale
- **Subscription Tracking**: Modello di subscription per gestire piani e accesso
- **Filament Admin Panel**: Risorse Filament complete per la gestione tenant
- **Automatic Connection Resolution**: Routing automatico verso la connessione database corretta

### Module Dependencies

- [Xot](../Xot/README.md) (required) — Core framework, abstractions, base models
- [User](../User/README.md) (required) — User management and authentication

---

## Quick Start

### Installation

```bash
# Already included in main project
# No additional setup required

# Publish migrations (if needed)
php artisan migrate
```

### Basic Usage

```php
use Modules\Tenant\Models\Tenant;
use Modules\Tenant\Models\Domain;

// Get current tenant
$tenant = Tenant::first();

// List all domains for a tenant
$domains = $tenant->domains()->get();

// Access tenant settings
$setting = $tenant->settings['key'] ?? null;
```

### Configuration

Configuration files:
- `config/config.php` — Module configuration, navigation, routes, providers
- `config/database.php` — Database connection isolation settings
- `config/metatag.php` — Meta tag configuration per tenant

Key settings:
- `routes.middleware` — Middleware stack for tenant routes (default: `['web', 'auth']`)
- `navigation.enabled` — Display in admin navigation (default: `true`)
- `navigation.sort` — Navigation sort order (default: `80`)

---

## Architecture

### Directory Structure

```
Tenant/
├── app/
│   ├── Models/              # 15 models (Tenant, Domain, TenantDomain, etc.)
│   ├── Actions/             # 15 business logic actions
│   ├── Services/            # 7 service classes (Config resolvers, etc.)
│   ├── Contracts/           # 2 interfaces and contracts
│   ├── Traits/              # 4 reusable model traits
│   ├── Filament/            # 7 Filament resources and components
│   ├── Http/
│   │   ├── Controllers/     # Controllers
│   │   ├── Middleware/      # Middleware for tenant routing
│   │   ├── Requests/        # Form requests
│   │   └── Livewire/        # Livewire components
│   ├── Console/Commands/    # 1 Artisan command
│   ├── Providers/           # 4 service providers
│   ├── Enums/               # Enumerations
│   └── View/                # Blade components
├── database/
│   ├── migrations/          # 3 migrations
│   ├── seeders/             # 9 seeders
│   └── factories/           # 8 model factories
├── config/
│   ├── config.php           # Module configuration
│   ├── database.php         # Database connection config
│   └── metatag.php          # Meta tag configuration
├── routes/                  # Route definitions (if any)
├── docs/
│   ├── index.md             # Documentation index
│   ├── README.md            # This file
│   ├── architecture.md      # Detailed architecture
│   ├── PATTERNS.md          # Design patterns
│   └── TROUBLESHOOTING.md   # Common issues
└── composer.json
```

### Core Models

- **Tenant** — Root multi-tenant entity with settings, domain, and metadata
- **Domain** — Domain/subdomain associations for tenant routing
- **TenantDomain** — Junction model linking tenant to multiple domains
- **TenantSetting** — Key-value configuration storage per tenant
- **TenantSubscription** — Subscription and plan tracking
- **DatabaseConfig** — Database connection configuration per tenant
- **TestSushiModel** — Testing utilities for Sushi model compatibility

### Key Components

**Actions**: Business logic organized by feature
- `Actions/Config/` — Configuration management
- `Actions/Domains/` — Domain/routing logic
- `Actions/Models/` — Model-level operations
- `Actions/Modules/` — Module integration
- `Actions/Translations/` — Localization support
- `Actions/Markdown/` — Markdown processing

**Services**: System services for isolation and configuration
- Configuration resolvers (per-tenant settings)
- Domain routing services
- Database connection management

**Filament Resources**: Admin panel interface
- DomainResource with custom forms and tables
- Form components for tenant management
- Pages for administration

---

## API Reference

See [API Documentation](API.md) for detailed model signatures, methods, and service interfaces.

---

## Usage Examples

### Creating a Tenant

```php
use Modules\Tenant\Models\Tenant;
use Modules\Tenant\Models\Domain;

// Create a new tenant
$tenant = Tenant::create([
    'name' => 'Customer Company',
    'slug' => 'customer-company',
    'domain' => 'customer.example.com',
    'database' => 'customer_db',
    'is_active' => true,
]);

// Add domains
Domain::create([
    'tenant_id' => $tenant->id,
    'domain' => 'customer.example.com',
    'is_primary' => true,
]);
```

### Accessing Tenant Data

```php
use Modules\Tenant\Models\Tenant;

// Query is automatically scoped to current tenant
$tenant = Tenant::currentTenant();

// Access related domains
$domains = $tenant->domains()->get();

// Get settings
$theme = $tenant->settings['theme'] ?? 'default';
```

### Setting Tenant Configuration

```php
$tenant = Tenant::find($id);
$tenant->settings = [
    'theme' => 'dark',
    'locale' => 'it',
    'timezone' => 'Europe/Rome',
];
$tenant->save();
```

---

## Testing

### Running Tests

```bash
# Run all module tests
php artisan test Modules/Tenant

# Run specific test class
php artisan test Modules/Tenant/Tests/Feature/TenantTest

# Run with coverage
php artisan test --coverage Modules/Tenant
```

### Test Factories

```php
use Modules\Tenant\Database\Factories\TenantFactory;

// Create test tenant
$tenant = TenantFactory::new()->create();

// Create with specific attributes
$tenant = TenantFactory::new()
    ->state(['name' => 'Test Tenant'])
    ->create();
```

---

## Troubleshooting

See [Troubleshooting Guide](TROUBLESHOOTING.md) for detailed information on:
- Isolation failures
- Context leaks
- Routing errors
- Database connection errors
- Permission issues
- Sync problems

---

## Related Modules

### Dependencies

- [Xot](../Xot/README.md) — Core framework and abstractions
- [User](../User/README.md) — User authentication and profiles

### Design Patterns

See [Patterns Documentation](PATTERNS.md) for:
- Tenant isolation pattern
- Database strategy selection
- Middleware scoping pattern
- Query scoping approach
- Context switching mechanisms

---

## Contributing

To contribute to the Tenant module:

1. Read [Architecture](architecture.md) for design principles
2. Follow patterns in [Patterns](PATTERNS.md)
3. Add troubleshooting entries for known issues
4. Keep isolation constraints in mind
5. Document complex business logic

See [Contributing Guide](../../docs/wiki/how-to/contributing.md) for details.

---

Navigation: [Project Home](../../docs/index.md) | [Modules](../../docs/modules/README.md) | [Documentation Index](index.md)
