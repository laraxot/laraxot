---
title: "Project Documentation — base_workorder_fila5 brownfield"
type: bmad-artifact
tags: [bmad, document-project, brownfield, laraxot, workorder]
created: 2026-07-27
updated: 2026-07-27
scan_date: 2026-07-27
track: bmad-method
qmd: "project documentation brownfield base_workorder modules filament xotbase scan"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - ./project-context.md
  - ./prd.md
  - ./architecture.md
  - ../docs/wiki/concepts/bmad-deep-code-documentation-religion.md
---

# Project Documentation — base_workorder_fila5

> **BROWNFIELD ground truth** — BMAD `bmad-document-project` (Create, deep scan 2026-07-27).  
> READ-ONLY sul codice. Alimenta PRD/architecture/stories. **Non inventare path.**

- **Scan date:** 2026-07-27
- **Codebase root:** `/var/www/_bases/base_workorder_fila5`
- **App root:** `laravel/`
- **BMAD track:** `bmad-method` (`bmad-output/config.yaml`)
- **SRC porting:** `/var/www/_bases/gestionale_commesse` (riferimento esterno)

---

## 1. Repository Structure

### Layout (depth ~2)

```
base_workorder_fila5/
├── AGENTS.md, CLAUDE.md          # stub router agenti
├── bashscripts/                  # tool, lock, QMD, audit, AI agents
├── bmad/                         # config BMAD + upstream plugin clone
├── bmad-output/                  # planning artifacts (questo doc)
├── docs/                         # wiki SSoT, stories, sprint-status, chat
├── laravel/                      # applicazione Laravel modulare
│   ├── Modules/                  # 38 moduli nwidart (module.json)
│   ├── Themes/                   # Sixteen, Zero, TwentyOne, Meetup, Barthelemy
│   ├── app/, bootstrap/, config/, database/, routes/, tests/
│   └── composer.json             # Filament 5 + nwidart (deps fine in moduli)
├── public_html/                  # web root deploy
├── gitmodules.ini                # mapping submodule moduli/temi
└── tests/, reports/, cache/
```

### Monorepo / Workspace Signals

- **Monorepo Laraxot** con moduli/temi come git submodule (vedi `gitmodules.ini`).
- Autoload root `composer.json`: `Modules\\` → `laravel/Modules/`.
- Workspace IDE: `_base_workorder_fila5.code-workspace`; per-modulo `_module_*.code-workspace`.

### Documentation Roots

| Path | Ruolo |
|------|--------|
| `docs/wiki/` | Second brain LLM (concepts, rules, memories, skills) |
| `docs/stories/` | Story BMAD implementazione |
| `docs/sprint-status.yaml` | Coda sprint |
| `docs/bmm-workflow-status.yaml` | Stato fasi BMAD |
| `laravel/Modules/*/docs/` | Wiki owner per modulo |
| `laravel/Themes/*/docs/` | Wiki owner per tema |
| `bmad-output/` | Planning (PRD, arch, epics, project-context) |

---

## 2. Stack and Toolchain

### Primary Language(s) and Runtime

| Language | Version | Evidence |
|----------|---------|----------|
| PHP | ^8.2 | `laravel/composer.json` `require.php` |
| JavaScript (Vite/theme) | node (tema) | `Themes/*/package.json` (se presente) |

### Framework(s)

| Framework | Version | Role |
|-----------|---------|------|
| Laravel | (via moduli / lock) | App HTTP + console |
| Filament | ^5.0 | Admin BO panels/resources |
| nwidart/laravel-modules | ^13 / `*` | Modularità |
| Livewire + Volt | * (Xot) | UI reattiva / FO |
| Folio | (tema pages/) | Routing FO file-based |

### Key Dependencies (evidenza)

**Root `composer.json`:** Filament tables/schemas/forms/infolists/actions/notifications/widgets/support ^5.1; `spatie/laravel-schemaless-attributes`.

**`Modules/Xot/composer.json` (framework base):** Filament 5, Livewire, Volt, Flux, nwidart ^13, Spatie (Data, Permission, QueueableAction, Health, ModelStates, Tags, Sluggable, ResponseCache, Media plugin, …).

**Dev (root):** `filament/upgrade`, `phpmd/phpmd`. PHPStan/Pest tipicamente in toolchain laravel/moduli (gate post-edit wiki).

### Test Framework(s)

- **Pest** / PHPUnit — `Modules/*/tests` (**1246** file PHP test aggregati al scan).
- **Nota:** no `RefreshDatabase`; `.env.testing` + SQLite discipline (wiki BMAD architecture).

### Container / Infrastructure

- `laravel/docker/` presente.
- Deploy: `deploy.sh`, `public_html/`.
- CI: `.github/` su root e su moduli/temi remoti.

---

## 3. Entry Points and Key Flows

### Application Bootstrap

| Entry File | Role |
|------------|------|
| `laravel/bootstrap/app.php` | `Application::configure` — web + console + `/up` |
| `laravel/bootstrap/providers.php` | `AppServiceProvider`, `AdminPanelProvider` |
| `laravel/public/index.php` | Front controller |
| `laravel/routes/web.php` | Thin — FO via Folio nei temi |
| `laravel/artisan` | CLI |

### Routing / UI flows

| Layer | Pattern | Evidence |
|-------|---------|----------|
| BO Admin | Filament Panel + `XotBaseResource` | 365 file con `extends XotBaseResource`; 136 `*Resource.php` |
| FO | Folio pages in tema | Sixteen **45** blade pages; TwentyOne **36**; Zero **3** |
| Domain logic | `Modules/*/app/Actions` + QueueableAction | **725** action PHP; **863** file con `QueueableAction` |
| Auth/User | Modulo User (Passport, Permission, Profile) | 104 models, 35 Filament resources |

### Residui da pianificare (non fixati qui)

- **29** namespace `Modules\...\Http\Controllers` ancora presenti (religione: no Controllers FO).
- **3** `extends Migration` Illuminate vs **306** `XotBaseMigration` (quasi allineato).
- `project-context.md` pre-scan diceva “zero moduli dominio” — **obsoleto** (vedi §4).

---

## 4. Module / Domain Structure

### Inventory (38 moduli con `module.json`)

Totali scan: **498** models · **725** actions · **136** Filament Resource · **300** migrations · **1246** test PHP · tutti con `docs/`.

| Modulo | alias | models | actions | Res | migr | tests |
|--------|-------|--------|---------|-----|------|-------|
| AI | ai | 4 | 30 | 1 | 4 | 13 |
| Activity | activity | 10 | 18 | 3 | 15 | 96 |
| AiAssistant | aiassistant | 5 | 2 | 0 | 4 | 5 |
| Billing | billing | 7 | 8 | 4 | 7 | 12 |
| Bom | bom | 2 | 0 | 1 | 2 | 3 |
| Catalog | catalog | 7 | 3 | 6 | 7 | 5 |
| Cms | cms | 21 | 10 | 5 | 3 | 142 |
| Compliance | compliance | 9 | 9 | 6 | 9 | 4 |
| Customer | customer | 6 | 8 | 2 | 5 | 17 |
| Document | document | 2 | 2 | 0 | 1 | 5 |
| Email | email | 4 | 4 | 0 | 3 | 3 |
| Employee | employee | 10 | 10 | 2 | 7 | 14 |
| EnergyBroker | energybroker | 6 | 11 | 1 | 6 | 4 |
| Fiscal | fiscal | 0 | 4 | 0 | 0 | 6 |
| Gdpr | gdpr | 13 | 7 | 6 | 7 | 40 |
| Geo | geo | 34 | 71 | 2 | 7 | 91 |
| HR | hr | 5 | 6 | 2 | 5 | 8 |
| Intervention | intervention | 9 | 23 | 2 | 8 | 28 |
| Inventory | inventory | 5 | 3 | 3 | 5 | 5 |
| Job | job | 34 | 15 | 9 | 14 | 35 |
| Lang | lang | 14 | 14 | 2 | 3 | 34 |
| Media | media | 8 | 49 | 3 | 3 | 18 |
| Notify | notify | 29 | 47 | 5 | 26 | 140 |
| Platform | platform | 2 | 0 | 2 | 2 | 7 |
| Production | production | 2 | 2 | 1 | 2 | 3 |
| PublicProcurement | publicprocurement | 15 | 6 | 3 | 15 | 4 |
| Quotation | quotation | 6 | 18 | 3 | 5 | 37 |
| Rating | rating | 13 | 3 | 4 | 6 | 4 |
| Seo | seo | 0 | 1 | 0 | 0 | 10 |
| Signature | signature | 3 | 2 | 0 | 2 | 6 |
| Tenant | tenant | 15 | 15 | 1 | 3 | 31 |
| TimberBilling | timberbilling | 30 | 33 | 13 | 29 | 36 |
| UI | ui | 5 | 6 | 0 | 3 | 37 |
| User | user | 104 | 57 | 35 | 62 | 149 |
| Vehicle | vehicle | 5 | 1 | 1 | 2 | 5 |
| WhatsApp | whatsapp | 4 | 5 | 0 | 3 | 8 |
| WorkOrder | workorder | 7 | 14 | 1 | 6 | 18 |
| Xot | xot | 43 | 208 | 7 | 9 | 163 |

### Domain vs platform (inferenza)

- **Core field-service presenti:** Customer, WorkOrder, Intervention, Quotation, Billing, Catalog, Signature, Document, Vehicle, …
- **Verticali:** TimberBilling, EnergyBroker, PublicProcurement, Compliance, Bom, Production, Inventory.
- **Canali:** Email, WhatsApp, AiAssistant, Notify.
- **Piattaforma:** Xot, User, Tenant, Cms, Geo, Lang, Media, UI, Activity, Gdpr, Job, HR, Employee, Rating, Seo, Platform, Fiscal, AI.
- `modules_statuses.json`: **40** enabled (include anche DbForge, FormBuilder — verificare presenza fisica).

### Model inheritance (campione)

- `Modules/{M}/app/Models/BaseModel` tipicamente `extends XotBaseModel` + `$connection` modulo.
- Domain models (`WorkOrder`, `Customer`, …) → `BaseModel` del modulo.
- Alcuni Billing → `XotBaseModel` diretto.
- User: Profile / Passport / Pivot specializzati.

---

## 5. Conventions and Patterns

| Area | Convenzione | Evidence |
|------|-------------|----------|
| Naming moduli | PascalCase singolare | `WorkOrder`, `Customer` |
| Models | `BaseModel` → `XotBaseModel` | WorkOrder/Customer BaseModel |
| Filament | Mai Filament diretto — `XotBase*` | 365 `XotBaseResource` |
| Actions | Spatie QueueableAction in `app/Actions` | 863 match |
| Migrations | `XotBaseMigration`, 1 create_* per model | 306 vs 3 Illuminate |
| FO | Folio + Volt — no Controllers | Controllers residui: 29 |
| Docs | `Modules/*/docs/wiki` + root wiki | tutti i moduli hanno `docs/` |
| Test | Pest, no RefreshDatabase | wiki architecture |
| Git | Forward-only | alwaysApply rules |
| Agenti | Second brain QMD on-demand | `.agents` stub layer |

### Error / logging / config

- Config Laravel `laravel/config/` + `config/config.php` per modulo (religione module-config).
- Multi-DB: `$connection` su BaseModel (es. WorkOrder `xot` / connection dedicata — verificare per modulo).

---

## 6. Integration Points

| Tipo | Note |
|------|------|
| Auth | User + Passport OAuth device codes |
| Permission | Spatie Permission (table names sacred) |
| Media/S3 | Media module Actions S3; Document Media (collision tabella `media` documentata in chat) |
| AI | Modules AI + AiAssistant |
| Messaging | Email, WhatsApp, Notify |
| Geo | Geo module (map/address) |
| Queue | Job module + QueueableAction |
| Filament panels | AdminPanelProvider + panel per modulo (dual registration religion) |

---

## 7. Planning Notes (gap / rischio — non fix)

1. **Document vs Media** tabella `media` — blocker documentato (`docs/chat/document-media-table-collision-blocker.md`).
2. **Pest D17** env phpunit vs `.env.testing` — P0 sprint.
3. Controllers FO residui da eliminare/migrare Folio.
4. Fiscal/Seo: 0 models in scan — verificare se solo Actions/config.
5. Aggiornare continuamente `project-context.md` dopo ogni wave (era desync).
6. BMAD religion: **prima di PRD/arch/story — document-project deep scan obbligatorio** (vedi wiki concept).

---

## 8. Recommended Next Steps

1. Aggiornare `bmad-output/architecture.md` se drift vs questa ground truth.
2. Continuare implementazione da `docs/sprint-status.yaml` (P0 D17 / 2.5 / 1.13).
3. Per ogni story: citare path reali da questo documento; aggiornare sezione modulo se toccata.

---

## Update log

| Date | Change |
|------|--------|
| 2026-07-27 | Create — deep 6-pass scan (document-project) |
