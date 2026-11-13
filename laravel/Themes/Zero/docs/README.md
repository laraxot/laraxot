---
id: zero-docs-readme
slug: readme
title: "Documentazione del tema Zero"
description: "Punto di ingresso della docs del tema Zero: scopo del tema, mappa delle cartelle, documenti chiave e regole di manutenzione."
document_type: index
type: index
category: documentation
status: active
module: Zero
tags: [zero, tema, docs, indice]
created: "2026-09-24"
updated: "2026-10-08"
qmd: "tema zero docs indice mappa cartelle bmad wiki stories archivio frontmatter duplicati"
issues: []
discussions: []
related:
  - ./inventory.md
  - ./wiki/index.md
  - ./bmad/00-index.md
---

# Documentazione del tema Zero

## Scopo del tema

`laravel/Themes/Zero` e' l'unico tema frontend del monorepo (tipo `pub`, vedi `theme.json`). Contiene solo presentazione: viste Blade, asset e traduzioni. La cartella `app/` e' vuota.

| Parte | Cosa c'e' | Dove |
|---|---|---|
| Viste | layout `app`, pagine `home`, `index`, login, componenti `layouts`, `blocks`, `ui`, override Filament per il login | `resources/views/` |
| Asset | Tailwind 3.4, Alpine 3, Flowbite 4, Vite 6 con `laravel-vite-plugin`; build in `public/` | `resources/css`, `resources/js`, `vite.config.js` |
| Mail | layout HTML `base`, `clean`, `christmas-professional` | `resources/mail-layouts/` |
| Lingue | `navigation` e `ui` in it, en, de | `lang/` |

Il tema non contiene logica di dominio. Survey, autorizzazione e dati Pulse sono del modulo Quaeris: contratto in [Quaeris docs, BMAD](../../../Modules/Quaeris/docs/bmad/README.md). Non copiare nel tema metriche o decisioni di dominio.

## Mappa delle cartelle

| Percorso | Ruolo | Ingresso |
|---|---|---|
| [`bmad/`](bmad/) | guide, architettura, report e pianificazione del tema (la parte piu' grande) | [`bmad/00-index.md`](bmad/00-index.md), [`bmad/README.md`](bmad/README.md) |
| [`wiki/`](wiki/) | conoscenza curata per gli agenti: concetti, regole, how-to, memorie, fonti | [`wiki/index.md`](wiki/index.md) |
| [`stories/`](stories/) | story BMAD del tema (posizione prevista dalla policy) | vedi sotto |
| [`bmad/stories/`](bmad/stories/) | altre due story, nate prima della policy | vedi sotto |
| [`concepts/`](concepts/) | vincolo XotBase al posto di Filament | [`xotbase-never-extend-filament.md`](concepts/xotbase-never-extend-filament.md) |
| [`skills/`](skills/) | note sulle skill del tema | [`readme.md`](skills/readme.md) |
| [`_archive/`](_archive/) | copie storiche, non canoniche; ogni file ha `canonical:` verso il documento vivo | [`index.md`](_archive/index.md) |

File nella radice: [`changelog.md`](changelog.md) (cronologia), [`inventory.md`](inventory.md) (conteggi verificati), [`index.md`](index.md) (indice storico, `status: superseded`: usare questa pagina).

## Documenti chiave

- Architettura: [`bmad/architecture.md`](bmad/architecture.md), [`wiki/rules/architecture-rules.md`](wiki/rules/architecture-rules.md), [`concepts/xotbase-never-extend-filament.md`](concepts/xotbase-never-extend-filament.md).
- Viste e UI: [`bmad/layouts.md`](bmad/layouts.md), [`bmad/components.md`](bmad/components.md), [`bmad/customization.md`](bmad/customization.md), [`bmad/mail-layouts.md`](bmad/mail-layouts.md).
- Grafici: [`bmad/filament-chart-integration.md`](bmad/filament-chart-integration.md), [`wiki/concepts/jpgraph-guide.md`](wiki/concepts/jpgraph-guide.md).
- Qualita': [`bmad/phpstan-dry-kiss-theme-guidelines.md`](bmad/phpstan-dry-kiss-theme-guidelines.md), [`bmad/php-quality-gates-rule.md`](bmad/php-quality-gates-rule.md).

## Story

- In `stories/`: [salute della docs, 2026-10-08](stories/2026-10-08-zero-docs-health.story.md), [audit documentazione](stories/docs-theme-zero-audit-2026-09-11.story.md), [audit indice](stories/docs-index-audit.story.md), [pulizia marker di conflitto](stories/zero-conflict-markers-cleanup-2026-09-22.story.md), [restyling login](stories/auth-login-ui-ux-redesign-2026-09-17.story.md).
- In `bmad/stories/`: [gate PHPStan e swarm](bmad/stories/quality-gates-phpstan-swarm-2026-09-23.story.md), [dedup e frontmatter](bmad/stories/theme-docs-dedup-frontmatter.story.md).

## Regole di manutenzione

1. Ogni `.md` apre con front matter completo (`id`, `slug`, `title`, `description`, `document_type`, `category`, `status`); regola in `bashscripts/ai/wiki/rules/markdown-file-naming-and-frontmatter.md`.
2. Un solo canonico per argomento. Il duplicato resta ma con `status: superseded` e `superseded_by`, oppure sta in `_archive/` con `canonical:`. Non cancellare, non rinumerare le story.
3. Nomi in kebab-case senza date (le story seguono la propria convenzione).
4. Nuove pagine nella cartella piu' specifica gia' esistente, con link dall'indice di quella cartella.

## Debito noto

- 30 file contengono ancora marker di conflitto Git con lati divergenti o annidati: elenco e stato in [`inventory.md`](inventory.md). Si risolvono uno per uno, tenendo entrambe le parti utili, in un task dedicato.
- Molti link relativi ereditati dalla vecchia struttura piatta non risolvono (conteggio in `inventory.md`).
- Le guide in `bmad/` citano Tailwind v4, Livewire 4 e Flux UI; il tema reale usa Tailwind 3.4, Alpine e Flowbite. Allineare quando si tocca la guida.
- Il gemello `bmad/readme.md` (maiuscola diversa) collide su filesystem case-insensitive: vedi [`bmad/README.md`](bmad/README.md).
