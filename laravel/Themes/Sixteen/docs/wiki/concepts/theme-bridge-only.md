---
title: "Theme bridge-only — Sixteen app/ remediation"
type: concept
module: Sixteen
tags: [sixteen, theme-first, bridge-only, ponytail, wave-6]
created: 2026-07-01
updated: 2026-07-01
qmd: "sixteen theme bridge only app services municipal migration ponytail wave 6"
related:
  - ./no-controllers-folio-volt-filament.md
  - ./second-brain-theme-boundary.md
  - ./x-page-data-bag-only.md
  - ../../ponytail-audit-over-engineering.md
  - ../../../../../docs/project/ponytail-audit-themes.md
---

# Theme bridge-only — Sixteen `app/` remediation

## Scopo

Sixteen violava **theme-first**: mini MVC in `app/` (Services, Models, Controllers, Livewire). Il tema deve essere **solo vestito**: layout, composizione shell, asset, view composer leggeri.

Riferimento produzione Predict: **TwentyOne** (bridge-only). Hub: [ponytail-audit-themes.md](../../../../../docs/project/ponytail-audit-themes.md).

## Cosa resta in `app/` (bridge)

| Artefatto | Ruolo |
|-----------|--------|
| `Providers/ThemeServiceProvider` | Viste `pub_theme`, config, composer, publish |
| `View/Composers/SixteenComposer` | Inietta `config('sixteen')` e menu statici |
| `Support/FrontofficeUrl` | URL CMS/nav (testati) |
| `Support/BlockCategoryRegistry` | Catalogo sottocartelle blocchi (testati) |
| `Console/Commands/*` | Install/publish tema |
| `Http/Controllers/ComuneController` | **Legacy** — route `comune.*`; migrare a Fixcity/Folio |

## Archiviato wave 6 (`.bak`)

Zero riferimenti prod **fuori** `Themes/Sixteen` per Services e Municipal.

### `app/Services/` → `Modules/User` o `Modules/Cms`

| File archiviato | Migrazione target |
|-----------------|-------------------|
| `CieAuthService.php.bak` | `Modules/User/app/Actions/Auth/CieAuthenticateAction.php` (QueueableAction) |
| `SpidAuthService.php.bak` | `Modules/User/app/Actions/Auth/SpidAuthenticateAction.php` |
| `MenuBuilder.php.bak` | Menu da `config/sixteen.menu` + eventuale `Modules/Cms` se dinamico da DB |
| `ThemeService.php.bak` | Metadati in `config/sixteen.php`; niente service wrapper |

### `app/Models/Municipal/` → `Modules/Cms` o `Modules/Fixcity`

| Modello archiviato | Migrazione target |
|--------------------|-------------------|
| `MunicipalNews`, `MunicipalEvent` | `Modules/Cms\Models\Page` / content types |
| `MunicipalService`, `PublicDocument` | `Modules/Fixcity` o Filament resource modulo owner |
| `OrganizationalUnit`, `PublicPerson`, `ContactPoint`, `MunicipalLocation` | `Modules/Cms` entities + seeders canonici |

### Altri orphan archiviati

| Path | Motivo |
|------|--------|
| `src.bak/` | Duplicato non autoload (`composer` → `app/` only) |
| `app/View/Components/Page.php.bak` | Sostituito da `Modules\Cms\View\Components\Page` |
| `app/Http/Livewire/Appointment/*` | Vietato Livewire FO; usare Filament widget modulo owner |
| `app/Models/Appointment.php.bak` | Dominio appuntamenti → modulo owner |
| `app/Http/Controllers/{Cie,Spid}AuthController.php.bak` | Auth digitale → `Modules/User` Actions |
| `routes/auth.php.bak` | Route SPID/CIE legate ai controller archiviati |
| `app/Filters/*.bak`, `Contracts/MenuFilterInterface.php.bak` | Solo MenuBuilder archiviato |
| `app/Events/BuildingSixteenMenu.php.bak` | Evento menu dinamico — YAGNI con config statica |

## Uso moduli TwentyOne / Predict

Sixteen **non** importa `Themes\TwentyOne`. Condivide pattern Laraxot via `Modules/Xot`:

- `XotBaseThemeServiceProvider` — bootstrap tema
- `RegisterBladeComponentsAction` — componenti Blade
- `Modules\Cms\View\Components\Page` — `<x-page>` canonico
- `Modules\User\Models\User` — utente (era referenziato da eventi auth archiviati)

Predict non ha dipendenze runtime su `Themes\Sixteen\app\*`.

## Ordine migrazione consigliato

1. **Auth SPID/CIE** → Actions in `Modules/User` + route in modulo (non tema)
2. **ComuneController** + `routes/web.php` `comune.*` → Folio pages + widget Fixcity
3. **Municipal models** → migrazioni + modelli nel modulo owner (1 modello = 1 migration + 1 seeder)
4. **MenuBuilder** → restare su `config/sixteen.menu` finché non serve menu da DB

## Verifica post-wave

```bash
# Nessun autoload di classi archiviate
rg 'Themes\\Sixteen\\(Services|Models\\Municipal)' laravel/Themes/Sixteen/app --glob '*.php' | grep -v '\.bak'

# PHPStan moduli intatti
cd laravel && ./vendor/bin/phpstan analyse Modules --memory-limit=-1
```

## Collegamenti

- [no-controllers-folio-volt-filament.md](./no-controllers-folio-volt-filament.md)
- [ponytail-audit-over-engineering.md](../../ponytail-audit-over-engineering.md)
- [ponytail-audit-remediation.md](../../../../../docs/project/ponytail-audit-remediation.md)
