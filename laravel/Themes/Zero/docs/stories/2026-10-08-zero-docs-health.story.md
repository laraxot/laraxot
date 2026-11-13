---
id: zero-docs-stories-2026-10-08-zero-docs-health
slug: 2026-10-08-zero-docs-health
title: "Salute della docs del tema Zero"
description: "Misura e bonifica della docs del tema Zero con le classi del gate audit-module-docs: front matter, conflitti banali, duplicati, indici, link."
document_type: story
type: story
category: bmad-story
status: done
module: Zero
tags: [docs, frontmatter, duplicati, indici, bmad]
created: "2026-10-08"
updated: "2026-10-08"
qmd: "tema zero docs salute frontmatter marker conflitto duplicati superseded indici link audit-module-docs"
issues: []
discussions: []
related:
  - ../bmad/stories/theme-docs-dedup-frontmatter.story.md
  - ./zero-conflict-markers-cleanup-2026-09-22.story.md
  - ./docs-theme-zero-audit-2026-09-11.story.md
---

# Salute della docs del tema Zero

Fase BMAD: Build, solo documentazione in `laravel/Themes/Zero/docs/`. Nessun codice toccato. Chiude i punti aperti di [theme-docs-dedup-frontmatter](../bmad/stories/theme-docs-dedup-frontmatter.story.md).

## Richiesta

La regola permanente dell'utente chiede che le docs di moduli e temi siano studiate, aggiornate e riordinate di continuo. Perimetro: la sola cartella docs del tema Zero (209 file `.md`).

## Analisi

Il tema e' un repo git a se (`laraxot/theme_zero_fila5`) e contiene solo presentazione: viste Blade, asset Tailwind 3.4, Alpine, Flowbite 4, Vite 6, layout mail e traduzioni. La docs era nata con una struttura piatta poi spostata in `bmad/`, quindi molti link storici puntano a file che non sono piu' dove erano.

Le classi sono quelle di `bashscripts/quality-gates/audit-module-docs.sh`, applicate alla sola cartella Zero con uno script a parte (il gate completo scansiona 31k file).

| Classe | Prima | Dopo |
|---|---:|---:|
| ZERO-BYTE | 0 | 0 |
| NO-FRONTMATTER | 25 | 0 |
| FRONTMATTER-INCOMPLETA | 184 | 0 |
| STUB-COMPATTO (conteggio del gate) | 8 | 0 |
| TRAP-DIR | 20 | 20 |
| STORY-MISPLACED (secondo il gate) | 4 | 4 |
| file con marker di conflitto Git | 95 | 0 |

Nota sullo stub: il gate conta le righe del front matter, quindi aggiungerlo porta il conteggio a 0 senza che gli stub diventino contenuto. Restano 31 file con al massimo 12 righe di corpo e senza puntatore a un canonico (elenco ricavabile con lo stesso criterio).

## Modifiche

1. Front matter completo su 209 file: aggiunte solo le chiavi mancanti (`id`, `slug`, `description`, `document_type`, `category`, `status`, piu' `type`, `tags`, `created`, `updated`, `qmd`, `issues`, `discussions` richieste da `verify-llm-wiki.sh`), senza toccare le chiavi esistenti. Valori ricavati da percorso, titolo e primo paragrafo; `category` e' euristica. 33 descrizioni deboli riscritte a mano.
2. Marker di conflitto: risolti per intero 65 file in modo senza perdita (un lato vuoto o lati identici; il lato non vuoto resta), piu' i blocchi banali di altri 7 file. Un caso risolto con evidenza sul filesystem: `concepts/xotbase-never-extend-filament.md`, dove nessuno dei due link risolveva e ora punta a `bashscripts/ai/wiki/rules/xotbase-critical-rules.md`; il suo titolo YAML era invalido ed e' stato corretto.
3. Duplicati, tutti mantenuti con puntatore: `bmad/changelog.md`, `bmad/jpgraph-guide.md`, `bmad/filament-5-nested-resources.md`, `bmad/git-collisions-bashscripts-audit.md`, `bmad/git-conflict-resolution-audit.md` sono `superseded` con `superseded_by`; i 19 file di `_archive/` hanno `canonical:` verso il documento vivo (18 con `status: archived`, 1 gia' `deprecated`); `index.md` e' `superseded` da `README.md`.
4. Rinomina con `git mv`: `bmad/git-conflict-resolution-2026-07-31.md` in `bmad/git-conflict-resolution-audit.md` (data nel nome). Riferimenti aggiornati in `bmad/readme.md` e `index.md`; gli altri riferimenti nel monorepo puntano ai file omonimi di altri moduli, non a questo.
5. Link: i relativi non risolti passano da 968 (inventario iniziale) a circa 510. Riparati dove il nome del file esiste una sola volta nella docs; i nomi omonimi ambigui (`readme.md`, `index.md`) sono lasciati.
6. Indici: `README.md` della docs riscritto (scopo reale del tema, mappa delle cartelle, documenti chiave, debito noto); `bmad/README.md` riscritto come mappa della cartella con tabella dei canonici; `wiki/index.md` completato con 15 concetti che non erano elencati.

## Verifica

- Script di audit con le classi del gate: vedi tabella sopra, eseguito a fine lavoro.
- Front matter YAML: PyYAML legge tutti i 209 file. A meta lavoro 14 non erano leggibili, tutti per cause preesistenti (13 con marker nel front matter, 1 con escape invalido) e poi sistemati.
- Id univoci: nessun duplicato tra i 209 `id`.
- `README.md`, `bmad/README.md` e `wiki/index.md`: 0 link relativi non risolti.

## Decisioni lasciate all'utente

1. `bmad/README.md` e `bmad/readme.md` differiscono solo per maiuscola e collidono su filesystem case-insensitive. Proposta: spostare `readme.md` (27 KB, ora con front matter) in `bmad/theme-overview.md` e aggiornare i riferimenti.
2. Il gate vuole le story in `docs/bmad/stories/`, la policy `modular-bmad-story-policy.md` in `docs/stories/`. Zero ha 4 story nella seconda e 2 nella prima. Nessuna spostata (la policy vieta di rinominare). Va allineato gate o policy.
3. Le due versioni `f1-world-champion-2026-theme-analysis` e `f1-world-champion-theme-analysis` divergono (la seconda ha righe in piu', alcune duplicate): serve scegliere il canonico.
4. Cartelle trappola: `_archive/` (19 file) e `wiki/raw/readme.md`. Proposta: restano come sono, escluse dalle ricerche di regole.
5. `wiki/how-to/mysql-remote.md` riporta in chiaro utente e password di un database. Non modificato: ruotare la credenziale e sostituire con un riferimento a `.env`.
6. `bmad/phpstan-merge-conflicts.md` e `bmad/laravel-13-upgrade.md` non hanno contenuto reale (solo titolo o riferimenti duplicati): da compilare o archiviare.
7. Tailwind v4, Livewire 4 e Flux UI citati in `bmad/00-index.md` non corrispondono al tema reale (Tailwind 3.4, Alpine, Flowbite).

## Aperto

- Circa 510 link relativi non risolvono ancora (percorsi di altri moduli, nomi ambigui, file mai esistiti). Vanno corretti per documento.
- Durante la sessione i 30 file rimasti con conflitti divergenti o annidati risultano risolti da un altro attore, non da questa story, scegliendo un lato (per esempio `index.md` da 669 a 397 righe). Il contenuto scartato resta in `git log` e va rivisto con `git diff ab378b9 -- docs`.
- Reindicizzazione QMD (`bash bashscripts/docs/llm-wiki-qmd.sh update`): la fa il coordinatore dello swarm.
