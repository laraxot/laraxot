---
id: committed-merge-markers-bootstrap-break-fix
slug: committed-merge-markers-bootstrap-break-fix
scope:
  - module:Xot
status: Done
epic: Quality Gates
priority: Critical
created: 2026-09-09
---

## Problema

`cd laravel && vendor/bin/pint --test --dirty` falliva con 4 parse error: conflitti
git `<<<<<<< HEAD` / `>>>>>>> laraxot/dev` **committati** a HEAD nel repo del modulo Xot
(commit `d7ae4b79`), non WIP non committato. Bloccava Pint e, a cascata, il bootstrap
di PHPStan su qualunque path che li includesse — stesso pattern già visto e chiuso il
2026-08-27 (conflitti committati in Notify che bloccavano il bootstrap globale).

File coinvolti:
- `app/Filament/Resources/Schemas/XotBaseResourceInfolist.php` — import/docblock `Component`
- `app/Helpers/ResourceFormSchemaGenerator.php` — path hardcoded divergenti fra i due lati
- `config/xot.php` — path hardcoded divergenti (`base_orisbroker_fila5` vs placeholder `<repo progetto>`)
- `tests/Unit/Docs/ModuleDocsAreProjectAgnosticTest.php` — versione HEAD (2026-09-08, baseline
  1642, scope solo `/docs/`) vs versione dev (2026-09-09, baseline 1286, scope tutti i `.md`
  di modulo esclusi vendor/node_modules/graphify-out, test README dedicato)
- `docs/stories/phpstan-xot-module-fix.md` — frontmatter con `project:` di **tre** progetti
  diversi (`base_workorder_fila5`, `<repo progetto>`, entrambi comunque estranei a questo
  checkout `base_techplanner_fila5`)

Anche il prompt operativo `bashscripts/docs/prompts/03-quality-gates.md` (non versionato,
`bashscripts/` è gitignored) aveva 30 righe di marker annidati: una versione concisa 3.23.0
in testa e un'intera versione verbosa precedente incollata in coda, coesistenti.

## Causa radice

Non WIP di sessione: contenuto committato. Path hardcoded assoluti in `config/xot.php` e
`ResourceFormSchemaGenerator.php` sono per loro natura non-portabili fra checkout diversi
(violano sia project-agnostic sia "niente path hardcoded") — la fusione a tre vie ha
scritto entrambi i lati sbagliati uno sopra l'altro invece di convergere su un valore
dinamico, e nessun gate l'ha intercettato perché il preflight cerca marker solo nei file
`.php` **nello scope di sessione dichiarato** (`SESSION_FILES`), non nell'intero repo ad
ogni esecuzione.

## Soluzione

1. `XotBaseResourceInfolist.php` — tenuto il lato dev (`use Component`, docblock `Component`
   breve, coerente con l'import).
2. `ResourceFormSchemaGenerator.php` — sostituito il literal hardcoded con
   `base_path('Modules/*/app/Filament/Resources/*Resource.php')`: portabile per costruzione,
   nessuna scelta arbitraria fra i due path sbagliati.
3. `config/xot.php` — stessa cura: `paths.*` e `module_paths.*` ora derivano da `base_path()`
   invece di stringhe assolute. Nessun uso rilevato altrove nel repo (`grep` su
   `xot.paths`/`xot.module_paths` vuoto), quindi cambio sicuro.
4. `ModuleDocsAreProjectAgnosticTest.php` — tenuto il lato dev per intero (più recente,
   scope più ampio, secondo test dedicato ai README, baseline già ricalcolata con la
   motivazione in commento). Ri-eseguito con Pest: la guardia **misura correttamente**
   ma il perimetro allargato (ogni `.md`, non solo `docs/`) trova **4487** occorrenze
   contro baseline 1286 — non è una regressione introdotta qui, è debito preesistente
   reso visibile dallo scope più ampio che il lato dev stesso introduceva. Non toccato:
   bonificare migliaia di README esula da questa story. Segnalato sotto.
5. `docs/stories/phpstan-xot-module-fix.md` — rimossa la riga `project:` (tre valori in
   conflitto, nessuno valido per questo checkout); uno story doc di modulo non deve
   nominare un progetto ospite comunque (project-agnostic).

## Verifica

```
php -l  → 4/4 OK
vendor/bin/pint --test <4 file>           → passed
vendor/bin/phpstan analyse <3 file non-test> --no-progress --memory-limit=-1 → [OK] No errors
vendor/bin/pest tests/Unit/Docs/ModuleDocsAreProjectAgnosticTest.php → 1/3 pass (2 falliscono
  su debito README preesistente, non su codice rotto da questa story)
```

## Acceptance criteria

- [x] Nessun `<<<<<<<`/`>>>>>>>` residuo nei 5 file toccati
- [x] `php -l` pulito su tutti
- [x] Pint verde sui 4 file PHP
- [x] PHPStan verde sui 3 file non-test (neon invariato, nessun `-c`/`--level`)
- [x] Nessun `mixed` introdotto, nessuna soppressione
- [ ] Baseline README-agnostic (4487 vs 1286) — **fuori scope**, segnalato all'utente

## Da fare (non in questa story)

- Ricalcolare/allineare `MODULE_DOCS_HOST_NAME_FILE_BASELINE` (4487 reale) o bonificare
  i README che nominano un progetto ospite — decisione dell'utente, numero enorme.
- Scansione repo-wide per altri conflitti committati fuori dallo scope Xot (il preflight
  del gate limita la scansione a `SESSION_FILES`; un run non a scope ha trovato 3 file
  rotti nel solo modulo Xot in un colpo).
