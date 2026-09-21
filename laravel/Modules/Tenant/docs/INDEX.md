<<<<<<< HEAD
# Documentation Index

Modulo: Tenant

## File disponibili

<!-- auto-generato: elencare i file .md presenti -->
=======
---
title: Tenant Module Documentation Index
module: Tenant
status: production
last_updated: 2026-07-28
---

# Tenant Module Documentation Index

**Last updated: 2026-07-28**

---

## Navigation

- [Overview](README.md) — Module description and quick start
- [Architecture](ARCHITECTURE.md) — System design and isolation strategies
- [API Reference](API.md) — Core models, contracts, and services
- [Patterns](PATTERNS.md) — Design patterns and best practices
- [Troubleshooting](TROUBLESHOOTING.md) — Common issues and solutions
- [Contributing](../../docs/wiki/how-to/contributing.md) — Development guidelines

---

## Module Statistics

**Last Generated: 2026-07-28**

| Category | Count | Description |
|----------|-------|-------------|
| Models | 15 | Tenant, Domain, TenantDomain, TenantSetting, TenantSubscription, etc. |
| Actions | 15 | Business logic components grouped by feature (Config, Domains, Models, etc.) |
| Services | 7 | Configuration resolvers, domain services, and utility services |
| Filament Resources | 7 | Admin panel resources and form components |
| Migrations | 3 | Database schema setup and evolution |
| Seeders | 9 | Database seed data for testing and development |
| Factories | 8 | Eloquent model factories for testing |
| Policies | 2 | Authorization policies for models |
| Traits | 4 | Reusable model traits |
| Providers | 4 | Service providers and registration |
| Contracts | 2 | Interfaces and abstraction contracts |
| Commands | 1 | Artisan console commands |

**Total: 78 PHP files**

---

## Recently Updated Files

| Date | File | Category |
|------|------|----------|
| 2026-07-28 | README.md | Documentation |
| 2026-07-28 | ARCHITECTURE.md | Documentation |
| 2026-07-28 | API.md | Documentation |
| 2026-07-28 | PATTERNS.md | Documentation |
| 2026-07-28 | TROUBLESHOOTING.md | Documentation |
| 2026-07-22 | Tenant.php | Model |
| 2026-07-22 | Domain.php | Model |
| 2026-07-22 | TenantDomain.php | Model |
| 2026-07-22 | TenantSubscription.php | Model |
| 2026-07-22 | TenantSetting.php | Model |

---

## Key Concepts

### Tenant Isolation
Each tenant operates in complete isolation:
- **Database Isolation**: Separate database per tenant or shared database with scoping
- **Configuration Isolation**: Per-tenant configuration settings
- **Domain Routing**: Automatic tenant resolution via domain/subdomain

### Core Models

- **Tenant**: Root tenant entity with settings and metadata
- **Domain**: Domain/subdomain associations for tenant routing
- **TenantDomain**: Junction model linking tenant to multiple domains
- **TenantSetting**: Key-value configuration storage
- **TenantSubscription**: Subscription and billing model

---

## Quick Links

- **Config**: `config/config.php`, `config/database.php`
- **Service Provider**: `app/Providers/TenantServiceProvider.php`
- **Contracts**: `app/Contracts/`
- **Tests**: `tests/` (refer to main project structure)

---

## Related Modules

- **[Xot](../../Xot/docs/INDEX.md)** — Core framework and abstractions
- **[User](../../User/docs/INDEX.md)** — User management and authentication

---

## Development Guidelines

1. Always maintain tenant isolation in queries
2. Use actions for complex business logic
3. Document patterns in PATTERNS.md
4. Add troubleshooting entries for known issues
5. Keep migrations atomic and reversible

For detailed development guidance, see [Contributing Guide](../../docs/wiki/how-to/contributing.md).
>>>>>>> laraxot/dev
