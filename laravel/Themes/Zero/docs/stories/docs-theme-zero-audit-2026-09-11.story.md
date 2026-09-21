---
title: "Audit e correzione docs/ — Theme Zero (2026-09-11)"
type: story
module: Zero
epic: null
story_id: null
slug: docs-theme-zero-audit-2026-09-11
status: done
cold_gate: null
created: '2026-09-11'
updated: '2026-09-11'
repository: "https://github.com/laraxot/theme_zero_fila5.git"
github_issue: null
github_discussion: null
estimated_effort: "3-4h"
blocked_by: []
blocks: []
supersedes: []
owned_scope:
  - "laravel/Themes/Zero/docs/index.md"
  - "laravel/Themes/Zero/docs/sprint-planning.md"
  - "laravel/Themes/Zero/docs/user-research.md"
  - "laravel/Themes/Zero/docs/CONFLICT-RESOLUTION-SUMMARY.md"
  - "laravel/Themes/Zero/docs/duplicate-methods.md"
  - "laravel/Themes/Zero/docs/duplicate-methods-report.md"
  - "laravel/Themes/Zero/docs/phpstan-level10-theme-compliance.md"
  - "laravel/Themes/Zero/docs/chartjs-datalabels-multiple-labels-complete-guide.md"
  - "laravel/Themes/Zero/docs/performance-actions-reference.md"
  - "laravel/Themes/Zero/docs/performance-calcolo-quota-troubleshooting.md"
  - "laravel/Themes/Zero/docs/conflict-resolution-summary.md"
  - "laravel/Themes/Zero/docs/chartjs-datalabels-filament5-implementation.md"
  - "laravel/Themes/Zero/docs/phpstan-merge-conflicts.md"
  - "laravel/Themes/Zero/docs/model-usage-in-themes.md"
  - "laravel/Themes/Zero/docs/stories/docs-index-audit.story.md"
related:
  - "./docs-index-audit.story.md"
  - "../naming-conventions.md"
  - "../model-docs-governance.md"
  - "../index.md"
  - "../00-index.md"
---

# Audit e correzione docs/ — Theme Zero (2026-09-11)

## Story

Come manutentore del monorepo, voglio un audit reale (non assunto) di
`Themes/Zero/docs/`, cosi' che la documentazione del tema — mai toccata prima
in questa serie di sessioni, a differenza dei moduli — smetta di contraddire il
codice reale e i file "canonici" smettano di essere vuoti mentre il contenuto
vero resta intrappolato in copie marcate deprecate.

## Contesto / Baseline

`Themes/Zero` e' l'unico tema del repo, con `.git` proprio (remote `laraxot` →
`github.com:laraxot/theme_zero_fila5.git`), 235 file `.md` sotto `docs/` (piu'
alcuni json/png non doc). Comandi eseguiti per stabilire la baseline (non
assunta):

```
find docs -type f -iname '*.md' | wc -l        # 235
find docs -maxdepth 1 -type d                  # 18 sottocartelle
git log -1 --format='%H %ad %s' --diff-filter=A -- docs/CHANGELOG.md
# 0d6cf4ce0b0b37f323e78afb5a624d242128aa9d 2026-09-09 17:08:27 +0200  "."
git log -1 --format=%P 0d6cf4c                 # vuoto: e' il commit radice del repo
```

## Acceptance Criteria

<!-- LOCKED. External dev tools must not edit Acceptance Criteria. -->

1. `docs/index.md` elenca tutti i 235 file `.md` reali (nessun file mancante,
   nessun link morto) — verificato con un diff link-vs-filesystem, non a occhio.
2. I file "canonici" (kebab-case) che risultavano vuoti mentre la loro variante
   "deprecated" conteneva il testo reale (`sprint-planning.md`,
   `user-research.md`) sono ripopolati col contenuto recuperato, senza cancellare
   le varianti deprecate.
3. Ogni variante `UPPERCASE`/`snake_case` byte-identica o quasi-identica a un
   file kebab-case canonico e' marcata `status: deprecated` in modo coerente
   (prima `CONFLICT-RESOLUTION-SUMMARY.md` non lo era, mentre le sue 3 varianti
   gemelle si').
4. Nessun file cancellato, rinominato o spostato: solo contenuto aggiunto/
   corretto (in linea con `docs-archive-policy.md` e lo standing order del
   monorepo).

## Esplicitamente fuori scope

- **Non ho toccato nulla fuori da `laravel/Themes/Zero/`** (nessun modulo,
  nessuna classe Table/Resource, nessun `.env`).
- **Non ho completato l'archiviazione reale**: `_archive/` contiene gia' copie
  di ~19 file (`ARCHITECTURE.md`, `CHANGELOG.md`, `PRD.md`, `README-en.md`,
  `FRAMEWORKS.md`, `00-INDEX.md`, `duplicate_methods_report.md`,
  `conflict*.md`, i 5 file `wiki/*/INDEX.md`, ecc.) ma gli originali NON sono
  mai stati rimossi da `docs/` root — l'archiviazione e' incompleta
  (duplicazione della duplicazione). Rimuovere i 19 originali da `docs/` root
  e' un'operazione a rischio piu' alto (tocca link esistenti da altri file) che
  ho preferito lasciare a una story dedicata piuttosto che farla di fretta.
- **Non ho toccato i duplicati dentro `wiki/`**: lo stesso pattern
  `INDEX.md`/`index.md`, `SCHEMA.md`/`schema.md` si ripete in
  `wiki/commands/`, `wiki/concepts/`, `wiki/memories/`, `wiki/rules/`,
  `wiki/skills/`. Documentato in `index.md`, non corretto: stesso ragionamento
  del punto precedente, piu' 39 file da rileggere uno per uno per essere sicuri
  di non perdere contenuto (come e' successo con `sprint_planning.md`).
- **Non ho toccato `graphify/graphify-out/graphify-out/`**: la cartella
  graphify-out risulta annidata due volte dentro se stessa
  (`docs/graphify/graphify-out/graphify-out/cache/...`), quasi certamente un
  bug di uno `graphify init`/copy eseguito due volte. E' cache, non
  documentazione redatta a mano, e considerarla "documentazione da migliorare"
  avrebbe forzato un parallelo che non esiste. Segnalato al coordinatore, non
  toccato.
- **Non ho verificato il 100% dei 235 file uno per uno** (impossibile in una
  sessione): ho letto/confrontato con `diff` tutte le ~18 coppie di duplicati
  in `docs/` root, la story esistente, i 2 file di governance
  (`naming-conventions.md`, `model-docs-governance.md`), e ho fatto uno
  screening automatico (script) per un secondo bug meccanico (voce duplicata
  adiacente in `related:` del frontmatter) su tutti i 235 file. Non ho letto
  a fondo il contenuto tecnico di ogni guida (es. i 5 file su ChartJS
  datalabels, ~90KB totali) per giudicarne l'accuratezza tecnica.

## Tasks / Subtasks

<!-- LOCKED. Ogni riga mappata a un AC. -->

- [x] Enumerare `docs/` (235 `.md`, 18 sottocartelle), confrontare con
      `docs/index.md` esistente (AC: 1)
- [x] Identificare la causa dei duplicati case/underscore: commit radice
      `0d6cf4c` (2026-09-09), non un merge di questa sessione — confermato
      dal commento esplicito gia' presente in `CHANGELOG.md` ("Merged from
      changelog.md, which collided with this file on case-insensitive
      filesystems") (AC: 1, 3)
- [x] Rigenerare `docs/index.md` per directory con script deterministico,
      aggiungere sezione "Duplicati noti" con canonico → variante per le ~18
      coppie in root, rimuovere il link morto a `legacy/` (AC: 1)
- [x] Confrontare `sprint-planning.md`/`user-research.md` (vuoti) con
      `sprint_planning.md`/`user_research.md` (marcati deprecated ma pieni),
      recuperare il contenuto nei file canonici (AC: 2)
- [x] Marcare `CONFLICT-RESOLUTION-SUMMARY.md` come deprecated, coerente con
      le altre 3 varianti (AC: 3)
- [x] Correggere bug meccanico: voce duplicata adiacente in `related:` del
      frontmatter, trovato con script su tutti i 235 file, presente in 10 file
      (`duplicate-methods.md`, `duplicate-methods-report.md`,
      `conflict-resolution-summary.md`,
      `chartjs-datalabels-filament5-implementation.md`,
      `chartjs-datalabels-multiple-labels-complete-guide.md`,
      `performance-actions-reference.md`,
      `performance-calcolo-quota-troubleshooting.md`,
      `phpstan-level10-theme-compliance.md`, `phpstan-merge-conflicts.md`,
      `model-usage-in-themes.md`) (AC: 4)
- [x] Aggiungere nota di aggiornamento a `docs-index-audit.story.md` (story
      precedente, "done" ma il cui deliverable e' stato sovrascritto dal
      commit radice) senza cancellarla ne' rinumerarla (AC: 4)

## Dev Notes

<!-- LOCKED. Ogni affermazione con [Source: ...]. -->

- [Source: `docs/naming-conventions.md#L255-L260`] — "Doc Files - Format:
  `kebab-case.md`" e' regola esplicita gia' scritta nel repo: la forma
  canonica dei nomi file doc e' kebab-case, non serve inventarla.
- [Source: `docs/model-docs-governance.md#L12`] — "Prefer canonical
  `kebab-case` docs filenames", stessa regola ribadita in un secondo file.
- [Source: `docs/CHANGELOG.md#L7`] — commento letterale nel file: "Merged from
  changelog.md, which collided with this file on case-insensitive
  filesystems." Conferma diretta, non dedotta, della causa dei duplicati.
- [Source: `git show --stat 0d6cf4c` nel repo tema] — tutte le ~18 coppie di
  duplicati in `docs/` root compaiono gia' nel primo commit della storia git
  attuale del repo (commit senza genitori, quindi storia ricreata), datato
  2026-09-09 17:08:27 +0200, autore "Marco Xot" — non introdotte da un merge
  visibile in questa storia git.
- [Source: `docs/product_roadmap.md#L1-L11`,
  `docs/product_strategy.md#L1-L11`, `docs/product_launch_plan.md#L1-L11`] —
  gia' marcati `status: deprecated` con nota "renamed to X, do not add dates
  or underscores in filenames"; il contenuto kebab-case corrispondente
  (`product-roadmap.md` ecc.) e' pero' una riscrittura nuova (italiano,
  "Documento vivente", 46 righe) non una migrazione del testo inglese
  originale (445+ righe) — quindi non e' un bug di perdita contenuto, sono
  due documenti diversi con lo stesso argomento. Diverso invece il caso di
  `sprint_planning.md`/`user_research.md`, dove i file canonici erano
  letteralmente vuoti (1 riga) — li' e' un bug reale, corretto in questa
  story.
- [Source: `docs/stories/docs-index-audit.story.md`] — story precedente
  (2026-09-03, status done) dichiara di aver riscritto `index.md` come indice
  "topic-organized" con sezione "Storico / da consolidare". L'`index.md` letto
  a inizio di questa sessione (179 righe, sezioni per cartella, nessuna
  sezione "Storico / da consolidare") non corrisponde a quella descrizione:
  sovrascritto dal commit radice del 2026-09-09, successivo alla story.

## Testing

<!-- LOCKED. -->

Story di sola documentazione, nessun codice applicativo toccato — testing
possibile e reale, eseguito:

```
# Nessun link morto in index.md
grep -oE '\]\(\./[^)]+\)' docs/index.md | sed 's/^](//;s/)$//' | sort -u \
  | while read -r p; do f="${p#./}"; [ -f "docs/$f" ] || echo "BROKEN: $p"; done
# output: vuoto dopo la correzione (prima: 1 link morto verso legacy/)

# Diff puliti (nessuna riga persa, solo la riga duplicata rimossa) sui 10 file
# del fix meccanico
git diff --stat docs/duplicate-methods.md docs/duplicate-methods-report.md \
  docs/conflict-resolution-summary.md \
  docs/chartjs-datalabels-filament5-implementation.md \
  docs/chartjs-datalabels-multiple-labels-complete-guide.md \
  docs/performance-actions-reference.md \
  docs/performance-calcolo-quota-troubleshooting.md \
  docs/phpstan-level10-theme-compliance.md docs/phpstan-merge-conflicts.md \
  docs/model-usage-in-themes.md
# atteso e verificato: "1 file changed, 1 deletion(-)" per ciascuno
```

Non applicabile: PHPStan/Pest (nessun file PHP toccato in questa story).

## Dependency Maps

Non blocca ne' e' bloccata da altre story. E' collegata (non blocca)
a `docs-index-audit.story.md`, di cui estende/corregge il deliverable.

## Owned File/Module Scope

Solo `laravel/Themes/Zero/docs/` (elenco in `owned_scope`). Nessun file PHP,
nessuna classe Filament, nessun modulo toccato.

## Learnings from Previous Stories

`docs-index-audit.story.md` (2026-09-03) aveva gia' fatto un lavoro analogo e
corretto sui 213 file dell'epoca, ma il deliverable e' stato perso non per
colpa sua: un evento esterno (ricostruzione della storia git del repo tema,
2026-09-09) lo ha sovrascritto silenziosamente. Lezione per il futuro:
un `index.md` "done" va riverificato contro il filesystem reale a ogni
sessione, non dato per buono solo perche' una story precedente dice di averlo
sistemato — esattamente il pattern gia' visto altrove nel monorepo
("Story gia' 'done' ma falsa").

## Dev Agent Record

### Agent Model Used

Claude Sonnet 5

### Completion Notes List

- 2026-09-11: audit completo di `docs/` (235 file), causa dei duplicati
  identificata e documentata, `index.md` rigenerato e verificato senza link
  morti, 2 bug reali di contenuto perso corretti (`sprint-planning.md`,
  `user-research.md`), 1 incoerenza di marcatura corretta
  (`CONFLICT-RESOLUTION-SUMMARY.md`), 10 file con bug meccanico
  (voce duplicata in frontmatter) corretti, story precedente aggiornata senza
  cancellarla.

### File List

- `docs/index.md` (riscritto)
- `docs/sprint-planning.md` (contenuto recuperato da `sprint_planning.md`)
- `docs/user-research.md` (contenuto recuperato da `user_research.md`)
- `docs/CONFLICT-RESOLUTION-SUMMARY.md` (marcato deprecated)
- `docs/duplicate-methods.md` (frontmatter corretto)
- `docs/duplicate-methods-report.md` (frontmatter corretto)
- `docs/conflict-resolution-summary.md` (frontmatter corretto)
- `docs/chartjs-datalabels-filament5-implementation.md` (frontmatter corretto)
- `docs/chartjs-datalabels-multiple-labels-complete-guide.md` (frontmatter corretto)
- `docs/performance-actions-reference.md` (frontmatter corretto)
- `docs/performance-calcolo-quota-troubleshooting.md` (frontmatter corretto)
- `docs/phpstan-level10-theme-compliance.md` (frontmatter corretto)
- `docs/phpstan-merge-conflicts.md` (frontmatter corretto)
- `docs/model-usage-in-themes.md` (frontmatter corretto)
- `docs/stories/docs-index-audit.story.md` (nota di aggiornamento aggiunta)

## Perche' `epic`/`story_id`/`github_issue`/`github_discussion` sono `null`

Nessun epic tecnico reale esiste per `Themes/Zero` (stesso ragionamento gia'
verificato per i moduli in `Modules/Xot/docs/stories/_TEMPLATE.story.md`), e
non e' stata aperta una issue/discussion GitHub per questo lavoro specifico:
inventarne una violerebbe la regola "mai fabbricare precedenti/riferimenti non
verificati". La story resta identificata da `slug` (univoco per costruzione)
finche' non viene aperta una issue reale.
