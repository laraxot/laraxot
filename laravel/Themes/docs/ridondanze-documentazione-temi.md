---
title: "Ridondanze documentazione temi (hub)"
type: concept
tags: [documentation, redundancy, themes, dry]
created: "2026-05-21"
updated: "2026-05-21"
related:
  - ./README.md
  - ../Sixteen/docs/wiki/concepts/ridondanze-documentazione-wizard.md
  - ../TwentyOne/docs/wiki/concepts/ridondanze-hub-twentyone-xot.md
  - ../../Modules/Xot/docs/wiki/concepts/ridondanze-cross-cutting-codebase.md
  - ../../Modules/docs/redundancy-report.md
---

# Ridondanze documentazione — temi Laravel

## Scopo

Inventario **editoriale** (non duplicare il report codice in `Modules/docs/redundancy-report.md`): dove la documentazione sotto `laravel/Themes/*/docs` si ripete tra temi, con moduli, o per slice parity voluta vs duplicabile.

## Volumi (ordine di grandezza, maggio 2026)

| Percorso | `.md` circa | Nota |
|----------|-------------|------|
| `Themes/Sixteen/docs/` | **1021** | Tema attivo FixCity / Design Comuni |
| `Themes/TwentyOne/docs/` | **196** | Tema alternativo (Predict/markets) |
| `Themes/docs/` (hub) | **24** | Governance condivisa |
| `Themes/Meetup/docs/`, `Barthelemy/docs/` | **12–19** | Quasi solo scaffold |
| `Modules/Sixteen/docs/` | **24** | **Fuori posto** — doc tema nel modulo |

**Non esiste** `Themes/One`; l’alternativa storica è **TwentyOne**.

## Pattern 1 — Scaffold LLM-wiki copiato (6×)

Stessi basename in hub + ogni tema + `Modules/Sixteen/docs/`:

- `ON-DEMAND-PATTERN.md`, `QMD-SETUP.md`, `PROJECT-STRUCTURE.md`, `PERFORMANCE-OPTIMIZATION.md`, `codex-error-fix.md`
- `wiki/{rules,skills,commands,memories}/INDEX.md`, `wiki/_templates/*`

**Canonico:** `laravel/Themes/docs/` (questa cartella). Negli altri temi: stub 5 righe + link relativo.

## Pattern 2 — Sixteen: wizard / parity / Design Comuni

Molte pagine **non sono byte-identiche** ma coprono lo stesso arco (refactor Filament, step segnalazione, CSS parity).

| Cluster | Esempi | Canonico |
|---------|--------|----------|
| Refactor narrativo | `wizard-refactor-explanation.md`, `ticket-wizard-filament-refactor.md` | Tenere explanation; technical come sezione o appendix |
| Governance DC | `design-comuni/TICKET-CREATION-WIZARD.md`, `wizard-governance-bridge.md` | Entrambi con “vedi anche” incrociato |
| Step privacy ×3 | `wiki/concepts/segnalazione-01-privacy-*` + 2× `wiki/comparisons/` | **Una** pagina concepts; comparisons deprecate o sezioni |
| Step dati ×2 | concepts + comparisons `segnalazione-02-dati-*` | `wiki/concepts/segnalazione-02-dati-design-comuni-vs-local.md` |
| HTML vs wiki | `visual-comparison/structure-analysis/*-html-comparison.md` | Appendice della pagina concepts step |
| Cartella `design-comuni/` (**181** file) | report sessione `VISUAL-*`, `SEGNALAZIONI_*` | Indice: `wiki/concepts/wizard-parity-documentation-map.md` + `design-comuni/00-INDEX.md`; nuovi report → `wiki/log.md` |

Dettaglio wizard Sixteen: [ridondanze-documentazione-wizard.md](../Sixteen/docs/wiki/concepts/ridondanze-documentazione-wizard.md).

## Pattern 3 — Overlap tema ↔ modulo (boundary)

| Topic | Tema (slice UI/parity) | Modulo (logica / API) |
|-------|----------------------|------------------------|
| Wizard Filament | Sixteen `wizard-*`, `architecture/wizard-parity.md` | Xot `xotbase-wizard-architecture.md`, `filament-haswizard-vs-xotbasewizard.md` |
| Segnalazione DC | Sixteen `segnalazione-*` | Fixcity `segnalazione-design-comuni-comparison.md` |
| Map / coordinate | Sixteen `coordinate-picker*`, `map-*` | Geo `map-picker-filament-field.md` |
| Login AGID | Sixteen ~40× `login*`, `auth/login*` | User (widget Filament, policy) — tema solo CSS/layout |
| DaisyUI | Sixteen `DAISYUI.md`, metriche | Cms analisi; Fixcity/Geo apply modulo |

**Regola:** prima di fondere, verificare se il file descrive **parity visiva** (resta nel tema) o **dominio** (va nel modulo).

## Pattern 4 — TwentyOne

| Cluster | Count | Canonico |
|---------|-------|----------|
| Filament login | ~24 file `filament-login*` | `filament-login-implementation.md` + `Modules/User/docs/` |
| Predict naming | `prediki_analysis.md` vs `prediki-analysis.md` | `predict/README.md` + un file kebab per competitor |
| Roadmap / homepage | molti `ROADMAP*`, `HOMEPAGE_*` | Un indice + un roadmap attivo |

Hub verso Xot: [ridondanze-hub-twentyone-xot.md](../TwentyOne/docs/wiki/concepts/ridondanze-hub-twentyone-xot.md).

## Pattern 5 — Indici e legacy Sixteen

Indici multipli (`INDEX.md`, `00-index.md`, `DOCUMENTATION_INDEX.md`, `MASTER_DOCUMENTATION.md`) → **`docs/wiki/index.md`** + **`docs/README.md`**.

Cluster **Vite** (8+ file), **header** (15+ `wiki/concepts/header-*`) → regole in un file SSoT (`vite-configuration-rules.md`, `header-ssot.md`); il resto link.

## Pattern 6 — `Modules/Sixteen/docs/`

Documentazione del tema **non** deve restare nel modulo: migrare contenuto vivo in `Themes/Sixteen/docs/` e lasciare stub che punta all’hub tema.

## Matrice azione (priorità)

| ID | Azione | Rischio se ignorato |
|----|--------|---------------------|
| T1 | Stub scaffold → `Themes/docs/` | Derive silenziose su 6 copie |
| T2 | Spostare `Modules/Sixteen/docs` → tema | Ricerca QMD fuori posto |
| T3 | Merge wizard refactor (2 file) | Tre narrative uguali |
| T4 | Dedup step 01-privacy / 02-dati | Confusione agent su SSoT step |
| T5 | Consolidare login AGID | 40 path stesso topic |
| T6 | TwentyOne filament-login cluster | Stesso di T5 lato altro tema |

## Collegamenti

- Codice + moduli: [ridondanze-cross-cutting-codebase.md](../../Modules/Xot/docs/wiki/concepts/ridondanze-cross-cutting-codebase.md)
- Inventario tecnico moduli: [redundancy-report.md](../../Modules/docs/redundancy-report.md)
- User legacy markdown: [ridondanze-docs-legacy-cluster.md](../../Modules/User/docs/wiki/concepts/ridondanze-docs-legacy-cluster.md)
