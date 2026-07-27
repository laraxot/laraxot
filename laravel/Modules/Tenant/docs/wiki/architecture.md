---
module: Tenant
concept: Architecture
last_updated: 2026-07-20
---

# Tenant Module Architecture

The **Tenant** module is the foundational layer for multi-tenancy: it resolves
which tenant a request belongs to, merges per-tenant config on top of the base
Laravel config, points the `tenant` DB connection (and per-module clones of it)
at the right database, and registers a self-healing Eloquent morph map — all
before business modules run.

## 1. Core Goal

**Connection-based isolation via per-tenant config directories**, not a
`tenant_id` column scope. Each tenant's `database.php` override (merged from
`config/{tenant-path}/database.php`) can point the `tenant` connection at a
distinct database/schema; models extending this module's `BaseModel` always
use the `tenant` connection (`protected $connection = 'tenant';`).

## 2. Request Flow

```text
Request → GetTenantNameAction (SERVER_NAME → config/ path)
        → TenantServiceProvider::boot()
            → mergeConfigs()      (every tenant config/*.php merged into Config)
            → registerDB()        (tenant connection + per-module connection clones)
            → registerMorphMap()  (Relation::morphMap from merged morph_map.php)
        → Business modules run, models resolve against `tenant` connection
```

1. **Identification** — `GetTenantNameAction` maps `SERVER_NAME` to a
   `config/` subdirectory path by filesystem existence checks (no DB lookup).
   See [TenantIdentification.md](./TenantIdentification.md).
2. **Config merge & DB setup** — `TenantServiceProvider` merges every config
   group found in the tenant directory, rebuilds `database.connections`
   (cloning the default connection for any enabled module lacking its own),
   and reconnects. See [ConfigurationDistribution.md](./ConfigurationDistribution.md).
3. **Morph map** — registered from the tenant's `morph_map.php`; unknown
   model aliases are resolved by scanning all enabled modules
   (`ResolveTenantModelClassAction`) and persisted back into `morph_map.php`
   (`SaveTenantConfigAction`), so the map grows on first use instead of
   requiring a manual entry per model.
4. **Execution** — business modules use plain Eloquent; isolation is enforced
   by the connection each model resolves to, not by application-level query
   scoping.

## 3. Key Components

| Component | Role |
|---|---|
| `Actions/GetTenantNameAction` | Resolves tenant name from `SERVER_NAME` → `config/` path |
| `Actions/Config/ResolveTenantConfigValueAction` | Merges one config group (tenant over global) into `Config` |
| `Actions/Config/*` (`GetTenantFilePathAction`, `SaveTenantConfigAction`, `GetTenantConfigNamesAction`, `MergeRecursiveStringKeyConfigAction`, `FilterConfigStringKeysAction`) | Supporting file/merge/persist primitives, each a single-purpose `QueueableAction` |
| `Providers/TenantServiceProvider` | Orchestrates config merge, DB connection rebuild, morph map registration at boot |
| `Models/BaseModel` | Abstract base forcing `protected $connection = 'tenant'` on every module model |
| `Models/Tenant` | Real-table Eloquent model for tenant *records* (billing/admin), not consulted during request-time identification |
| `Models/Domain`, `Models/TenantDomain` | `Sushi` (in-memory) models listing known tenant config paths, for admin UI use |
| `Actions/Models/ResolveTenantModelClassAction` | Resolves + self-heals the `morph_map` entry for a given model alias |
| `Filament/Resources/DomainResource` | Admin UI over the `Domain` Sushi model |

## 4. Sacred Rules

- All models in business modules **must** extend a `BaseModel` that forces the
  `tenant` connection — this is the isolation boundary, not a runtime
  `WHERE tenant_id = ?` scope.
- **No Services** — tenant logic is implemented as `Spatie\QueueableAction`
  classes with a single `execute()`, invoked via `app(X::class)->execute()`.
  There is no `TenantService` class in this codebase.
- **Never delete files under `laravel/config/`** without grepping for
  references first — these are real tenant configs, not scaffolding.
- `morph_map.php` in each tenant config directory is load-bearing for
  polymorphic relations; treat it as generated-but-persistent, not disposable.

---
**Related Pages:**
- [TenantIdentification.md](./TenantIdentification.md)
- [ConfigurationDistribution.md](./ConfigurationDistribution.md)
