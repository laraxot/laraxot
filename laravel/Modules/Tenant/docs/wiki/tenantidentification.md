---
module: Tenant
concept: Tenant Identification
last_updated: 2026-07-20
---

# Tenant Identification

The tenant for the current request is resolved by **`GetTenantNameAction`**
(`app/Actions/GetTenantNameAction.php`), a `Spatie\QueueableAction` invoked via
`app(GetTenantNameAction::class)->execute()`. It runs during `LoadConfiguration`
(before facades are available), so it reads `getenv('SERVER_NAME')` directly
instead of the `Request` facade.

## 1. Resolution Algorithm

The tenant is **not** looked up in a database table or a domain-mapping model.
It is resolved to a **config directory path** under `laravel/config/`:

1. Take `SERVER_NAME` (fallback: host part of `config('app.url')` if `SERVER_NAME`
   is empty or `127.0.0.1`).
2. Strip a leading `www.`, split on `.`, slugify each segment, and **reverse** the
   parts (so `geekpiu.com` → `['com', 'geekpiu']`).
3. If `config/{parts joined by /}` exists as a directory → that path (`.`-joined,
   e.g. `com.geekpiu`) is the tenant name.
4. Else, if dropping the last part still yields an existing `config/` directory,
   use that (handles subdomains, e.g. `admin.geekpiu.com` → `com/geekpiu`).
5. Else, fall back to the reversed-and-joined default host path
   (`config('app.url')`) if that directory exists.
6. Else, fall back to the literal string `'localhost'`.

This matches the on-disk layout described in the repo root `CLAUDE.md`-adjacent
convention:

```text
laravel/config/
├── localhost/         # local dev, single DB, all tenants
├── com/geekpiu/        # production tenant "GeeKPIU"
├── eu/fixcity/          # production tenant "FixCity"
└── net/futurely/        # production tenant "Futurely"
```

There is no subdomain→tenant-slug mapping table and no "custom domain" Sushi
lookup in this action — identification is purely **filesystem-path matching**
against `config/`.

## 2. What `Domain` / `TenantDomain` Sushi Models Are For

`Modules\Tenant\Models\Domain` and `Modules\Tenant\Models\TenantDomain` use the
`Sushi` trait (in-memory/array-backed models, no real table). Their rows come
from `GetDomainsArrayAction`, which recursively walks `config_path()` and
collapses every leaf directory (skipping `lang/`) into a flat list of
dot-joined tenant path names (e.g. `com.geekpiu`). These models expose the
**list of known tenant config paths** for Filament UI / admin selection —
they are a read view over the same `config/` directories `GetTenantNameAction`
resolves against, not an independent domain-routing table.

The `Tenant` Eloquent model (with `domain`, `database`, `slug` columns) is a
separate, real-table model for tenant *record management* (billing, users,
subscriptions); it is not consulted by the identification flow above.

## 3. Implementation Entry Points

- `app/Actions/GetTenantNameAction.php` — the resolver described above.
- `app/Actions/Config/GetTenantFilePathAction.php` — builds
  `config/{tenantName}/{filename}`, rejecting path traversal; used by
  `GetTenantConfigArrayAction` / `SaveTenantConfigAction`.
- `app/Actions/Config/GetTenantConfigPathAction.php` — turns a config key into
  the tenant-scoped dotted config key (`str_replace('/', '.', $name).'.'.$key`).

## 4. Usage Pattern

```php
$tenantName = app(\Modules\Tenant\Actions\GetTenantNameAction::class)->execute();
```

There is no `TenantService` facade/class in the current codebase — all tenant
logic is exposed through `QueueableAction`s called via `app(X::class)->execute()`.

## 5. Caching

Resolution itself is a cheap filesystem check per request (no explicit cache
layer in the Action). The *merged config* built from the resolved tenant name
is cached for the request lifetime by `ResolveTenantConfigValueAction` writing
back into Laravel's `Config` repository (see
[ConfigurationDistribution.md](./ConfigurationDistribution.md)).

---
**Related Pages:**
- [Architecture.md](./Architecture.md)
- [ConfigurationDistribution.md](./ConfigurationDistribution.md)
