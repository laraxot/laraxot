# Xot Module - concepts Index

## Purpose
Index for Xot module concepts.

## On-Demand Loading

```bash
qmd search "Xot concepts" --limit 5
```

## Moduli & skeleton (2026-07-27)

- [module-config-php-religion.md](./module-config-php-religion.md) — **obbligatorio** `Modules/{M}/config/config.php`
- [module-dashboard-page-mandatory.md](./module-dashboard-page-mandatory.md) — **obbligatorio** `app/Filament/Pages/Dashboard.php`
- [module-admin-panel-provider-mandatory.md](./module-admin-panel-provider-mandatory.md) — `AdminPanelProvider` + `module.json`
- [module-config-config-php.md](./module-config-config-php.md) — contratto campi panel
- [module-model-artifact-parity.md](./module-model-artifact-parity.md) — N modelli = N migrate + factory + seeder
- [composer-root-skeleton-modular.md](./composer-root-skeleton-modular.md) — Root Composer minimo
- [module-testcase-xotbase-hierarchy.md](./module-testcase-xotbase-hierarchy.md) — TestCase → XotBaseTestCase

## Migrazioni & Filament

- [migration-foreign-id-for.md](./migration-foreign-id-for.md) — FK via modello
- [xotbase-filament-widget-hierarchy.md](./xotbase-filament-widget-hierarchy.md)
- [no-app-support-queueable-actions.md](./no-app-support-queueable-actions.md) — Actions, non Services

## PHPStan & qualità

- [phpstan-trait-probes.md](./phpstan-trait-probes.md) — trait `unused` → probe host
- [trend-action-delegation.md](./trend-action-delegation.md) — Action come confine
- [Ridondanze cross-cutting (hub)](./ridondanze-cross-cutting-codebase.md)

## Composer

- [composer-merge-plugin-modules-only.md](./composer-merge-plugin-modules-only.md) — merge solo moduli, mai temi

## See Also

- [Root Trigger Map](../../../../../docs/wiki/rules/00-TRIGGER_MAP.md)
- Hub temi: [runtime-config-religion-hub](../../../../Themes/docs/shared-components/runtime-config-religion-hub.md)
- Audit: `bash bashscripts/tools/audit-module-config-php.sh`

---
*Updated: 2026-07-27*
