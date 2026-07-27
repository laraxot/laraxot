---
module: Tenant
concept: Configuration Distribution
last_updated: 2026-07-20
---

# Configuration Distribution

Each tenant gets configuration overrides via a per-tenant directory under
`laravel/config/`, merged on top of the base Laravel config at runtime. There
is no `TenantService` class in the codebase — the mechanism is a set of
`Spatie\QueueableAction`s called via `app(X::class)->execute()`.

## 1. Directory Structure

```text
laravel/config/
├── app.php                 # base/global config
├── database.php
├── localhost/               # local dev tenant
│   ├── app.php
│   └── database.php
├── com/geekpiu/              # production tenant, nested by reversed domain
│   ├── app.php
│   ├── database.php
│   ├── services.php
│   ├── modules.php
│   ├── modules_statuses.json
│   └── morph_map.php         # CRITICAL — Eloquent morph map, never delete
└── eu/fixcity/
    └── ...
```

Directory names are the reversed-domain path produced by `GetTenantNameAction`
(see [TenantIdentification.md](./TenantIdentification.md)), not arbitrary
tenant slugs.

## 2. Merging Logic — `ResolveTenantConfigValueAction`

`app/Actions/Config/ResolveTenantConfigValueAction.php` resolves a single
dotted config key (e.g. `app.name`, `database.connections.tenant`):

1. Take the first segment of the key as the config **group** (e.g. `app`).
2. Load the current global `config($group)`.
3. Load the tenant override at `config("{tenantName with / → .}.{group}")`
   (e.g. `com.geekpiu.database`).
4. Recursively merge tenant-over-global via
   `MergeRecursiveStringKeyConfigAction` (`array_replace_recursive`, filtering
   to string keys only via `FilterConfigStringKeysAction`).
5. Write the merged array back into the Laravel `Config` repository for that
   group (`Config::set($group, $merged)`) — so subsequent plain `config()`
   calls for that group return tenant-aware values for the rest of the
   request.
6. Return the resolved value.

Because step 5 mutates the shared `Config` repository, calling this action for
a group once is enough to make ordinary `config('app.name')` calls tenant-aware
afterward — but only for groups that have actually been resolved.

## 3. Where Merging Is Triggered — `TenantServiceProvider`

`app/Providers/TenantServiceProvider::boot()`:
- `mergeConfigs()` — enumerates every `.php` file in the tenant's config
  directory (`GetTenantConfigNamesAction`) and calls
  `ResolveTenantConfigValueAction` for each, so all tenant config groups get
  merged early in the boot cycle.
- `registerDB()` — resolves the merged `database` config, ensures a `user`
  connection alias exists, clones the default connection for every enabled
  module that doesn't define its own (`mergeModuleConnections`), sets
  `Schema::defaultStringLength(191)`, replaces `Config::set('database', ...)`
  wholesale, and reconnects (`DB::purge` + `DB::reconnect`) — except during
  testing, where reconnect is skipped.
- `registerMorphMap()` — resolves the merged `morph_map` config and registers
  it via `Relation::morphMap()`.

## 4. Writing Tenant Config — `SaveTenantConfigAction`

`app/Actions/Config/SaveTenantConfigAction.php` reads the existing tenant
config file (if any), deep-merges new data on top (`array_merge_recursive`
distinct-by-key, not `array_replace_recursive`), sorts keys recursively, and
writes it back via `SaveArrayAction` (Xot module). Used e.g. by
`ResolveTenantModelClassAction` to persist newly discovered morph-map entries
back into `morph_map.php` — the morph map is self-healing rather than fully
static.

## 5. Usage Pattern

```php
// tenant-aware — merges tenant override before reading
$appName = app(\Modules\Tenant\Actions\Config\ResolveTenantConfigValueAction::class)
    ->execute('app.name');

// plain config() is only tenant-aware for groups already resolved
// (all groups are resolved once at boot via TenantServiceProvider::mergeConfigs())
$appName = config('app.name');
```

## 6. Security

`GetTenantFilePathAction` rejects any filename containing `..`, a leading `/`,
or a null byte before building a path under the tenant's config directory,
preventing path traversal into another tenant's config or outside `config/`
entirely.

---
**Related Pages:**
- [Architecture.md](./Architecture.md)
- [TenantIdentification.md](./TenantIdentification.md)
