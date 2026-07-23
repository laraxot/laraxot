---
title: "Hub ridondanze TwentyOne ↔ Xot core"
type: concept
theme: twentyone
created: "2026-05-21"
updated: "2026-05-26"
related:
  - ../../analisi-metodi-duplicati.md
  - ../../dry-kiss-analysis.md
  - ../../../../../Modules/Xot/docs/wiki/concepts/ridondanze-cross-cutting-codebase.md
  - ../../../../../Modules/Xot/docs/redundancy-report.md
---

# Ridondanza: perché questo file esiste nel tema TwentyOne

## Scopo

Evitare un **nuovo mega-report tecnico**: il tema ha già analisi voluminose (**`analisi-metodi-duplicati.md`**, **`dry-kiss-analysis.md`**). Qui si indica solo **chi è owner canonico delle ridondanza-inventari trasversali** e come incrociare i documenti tema.

## Documenti storici dentro TwentyOne (`docs/`)

| File | Cosa misura |
|------|-------------|
| [`analisi-metodi-duplicati.md`](../../analisi-metodi-duplicati.md) | Scan quantitativo replicazioni pattern Filament/List (`getTableColumns` ecc.) novembre 2025 |
| [`dry-kiss-analysis.md`](../../dry-kiss-analysis.md) | Valutazione DRY/KISS del tema TwentyOne sullo stato del repo stesso giorno |

**Nota temporale**: numeri LOC / occorrenze **non sono auto-aggiornati** — dopo refactor grandi ricontrollare con `rg`/PHPStan prima di affidarsi alle tabelle storiche.

## Componenti byte-identici vs Sixteen (2026-05-23)

Scan statico cross-tema: esistono file **`placeholder.blade.php`** con **stesso contenuto** tra **TwentyOne** e **Sixteen** (e due varianti duplicate anche **dentro** Sixteen: `components/ui` vs `components/utilities`). Non è solo “stile”: implica **doppia manutenzione** su token markup/classi.

**Politica:** una sola SSoT per il contratto visivo del placeholder; l’altro tema **include** o **estende** esplicitamente (documentare la scelta in issue #90 / #89). Dettaglio numeri globali: [`byte-identical-files-static-scan.md`](../../../../../Modules/Xot/docs/wiki/redundancy/byte-identical-files-static-scan.md).

## Hub globale modulo Xot

L’analisi continuativa degli “anti-pattern di duplicazione” **cross-module** vivono in modulo **core**:

- **[ridondanze-cross-cutting-codebase.md](../../../../../Modules/Xot/docs/wiki/concepts/ridondanze-cross-cutting-codebase.md)** (scaffold LLM‑wiki ripetuti, wizard doc pair, liste moduli cluster)
- **[`audit-profondo-ridondanze-holistic.md`](../../../../../Modules/Xot/docs/wiki/redundancy/audit-profondo-ridondanze-holistic.md)** — dopo 2026-05-26 include **§ analisi nominale classe / pivot / ThemeComposer CMS** accanto allo scan checksum.

## Dove documentare dopo una nuova scoperta

1. Ridondanza **PHP condivisa** dai moduli → append in **`redundancy-report.md`** (sezione modulo Xot).
2. Ripetizioni **solo doc TwentyOne** (naming kinetic/GSAP/login widget) → aggiungi sottoforma di link in wiki esistenti o nella tabella Compiled Pages tema.
3. Policy multi-tema/multi-modulo → pagina **`ridondanze-cross-cutting`** e root wiki se vale regola globale (`docs/wiki`).
