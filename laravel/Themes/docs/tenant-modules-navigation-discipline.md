---
title: tenant modules navigation discipline
type: concept
tags: [themes, modules, tenant, navigation, modules_statuses, filament]
created: 2026-07-27
updated: 2026-07-27
qmd: "themes modules navigation GetModulesNavigationItems modules_statuses tenant workorder"
related:
  - ./gc-modules-runtime-matrix.md
  - ./module-vs-theme-boundaries.md
  - ./gestionale-docs-index.md
  - ../../Modules/Tenant/docs/tenant-module-status-registry.md
  - ../../Modules/Tenant/docs/it/config/modules-statuses.md
  - ../../Modules/WorkOrder/docs/modules-statuses-workorder.md
---

# Themes e registro moduli tenant — cosa imparare

## Perché i Themes devono conoscere questo

I temi (Sixteen, Zero, …) **non** ospitano la BL gestionale in Wave 1, ma l’operatore vede i pannelli Filament costruiti da `GetModulesNavigationItems` — che legge `config/local/workorder/modules_statuses.json`, non il tema.

Un FO perfetto + moduli bootati + **menu admin vuoto o incompleto** = quasi sempre registro tenant stale, non bug del tema.

## Tre file, tre ruoli (non confonderli)

| File | Effetto |
|------|---------|
| `laravel/modules_statuses.json` | Boot nwidart (service provider, migrate) — default globale |
| `config/local/workorder/modules.php` | Override activator → punta al JSON tenant workorder |
| `config/local/workorder/modules_statuses.json` | **Navigazione admin** + lista tenant (`GetTenantModulesAction`) |

`Modules/{Name}/config/config.php` è un **quarto** artefatto: metadati panel (name, icon, sort) — non abilita/disabilita il modulo.

## Impatto sui temi

| Cosa | Dove vive |
|------|-----------|
| Folio/Volt FO, CSS, parity Design Comuni | `Themes/{Name}/` |
| Panel operator `*/admin`, Resources, Actions | `Modules/{Name}/` |
| Landing `{modulo}/admin` | `Modules/{Name}/app/Filament/Pages/Dashboard.php` → `XotBaseDashboard` |
| Voce menu panel compare? | Solo se nome modulo è nel JSON tenant **e** esiste `Modules/{Name}/` |

Themes **non** editano `modules_statuses.json`. Segnalano sintomi (menu mancante) e linkano [tenant-module-status-registry.md](../../Modules/Tenant/docs/tenant-module-status-registry.md).

## Lezioni sessione 2026-07-27

1. **File copiato da altro `base_*`** — JSON con Quaeris, Ticket, Shop…; `GetTenantModulesAction` filtra in silenzio → nessun errore, menu sbagliato.
2. **Fantasma nel JSON** — `DbForge`, `FormBuilder` senza directory `Modules/` → stesso filtro silenzioso.
3. **Directory senza `module.json`** — `Blog`, `Comment`, `TestModule` non sono moduli nwidart; non vanno nel registro finché non hanno `module.json`.
4. **Audit scoped sbagliato** — controllare solo `Modules/*/config/config.php` non aggiorna il registro tenant sotto `config/local/workorder/`.

## Checklist (agenti / maintainer)

```bash
cd laravel

# Moduli reali (SSoT fisico)
php -r '$d=array_filter(scandir("Modules"),fn($x)=>is_dir("Modules/$x")&&file_exists("Modules/$x/module.json")); sort($d); echo count($d),"\n";'

# Diff root vs tenant workorder
diff <(jq -r 'keys[]' modules_statuses.json | sort) \
     <(jq -r 'keys[]' config/local/workorder/modules_statuses.json | sort)

# Live navigation (non fidarsi solo del file)
php artisan tinker --execute="print_r(app(\Modules\Tenant\Actions\Modules\GetTenantModulesAction::class)->execute());"
```

## Riferimenti per tema

- [module-dashboard-page-mandatory.md](../../Modules/Xot/docs/wiki/concepts/module-dashboard-page-mandatory.md) — landing panel BO
- [module-vs-theme-boundaries.md](./module-vs-theme-boundaries.md) — confini layer
- [gc-modules-runtime-matrix.md](./gc-modules-runtime-matrix.md) — matrice enable + rischi runtime
