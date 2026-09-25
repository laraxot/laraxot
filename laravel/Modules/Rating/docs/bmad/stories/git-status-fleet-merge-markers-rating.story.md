---
title: "Bonifica marker merge committati — Rating"
type: story
module: Rating
epic: quality
story_id: "git-status-fleet-merge-markers-rating"
status: done
track: quality/fleet
related:
  - ../../../Xot/docs/bmad/stories/merge-marker-fleet-residue.story.md
---

# git-status-fleet-merge-markers-rating

## Contesto

Il processo automatico "laraxot" ha committato marker di merge conflict non
risolti (`<<<<<<<`, `=======`, `>>>>>>>`, varianti diff3 a 8 char) in file docs.
Strategia canonica (story Xot `merge-marker-fleet-residue`): HEAD pulito →
`git checkout HEAD -- file`; HEAD sporco → restore blob ultimo commit pulito in
history. Tool: `bashscripts/tools/resolve-merge-markers.sh` (ignora marker
dentro code fence).

## Git status iniziale

- Branch: `dev`, up to date con `laraxot/dev`, working tree clean.
- `.gitattributes`: nessun marker.

## Risultati

| Metrica | Valore |
|---|---|
| File candidati (grep marker) | 42 |
| RESTORE_HEAD (HEAD pulito) | 0 |
| RESTORE_HIST (blob ultimo commit pulito) | 39 |
| MANUAL / MANUAL_UNTRACKED | 0 |
| Skipped (marker solo dentro fence) | 3 |
| `git status --porcelain` post-apply | 39 |

Tutti i 39 file ripristinati dal commit pulito `0e26fee8`. File con
`commits_reverted` più alto: `docs/architecture.md` (12). Campionato: il blob
ripristinato contiene già una sezione "Note storiche" che assorbe il contenuto
del vecchio `ARCHITECTURE.md` (features, integration, review system);
il lato HEAD del conflitto era duplicato/rumore di merge — nessuna perdita
rilevante.

## MANUAL irrisolti

Nessuno.

## Verifica finale

- Zero marker `<<<<<<<`/`=======`/`>>>>>>>`/`\|\|\|\|\|\|\|` fuori dai code
  fence nel worktree.
- Nessun commit effettuato (modifiche solo in worktree, come da consegna).

## Pest

Skip: intervento solo su file documentazione (`.md`), nessun comportamento PHP
modificato.

## Review blocchi divergenti

Seconda passata (merge `--allow-unrelated-histories` da `laraxot/dev` committato
con marker): i 20 blocchi divergenti del pack `/tmp/review-Rating.md` (12 file)
sono stati ragionati caso per caso. Nel worktree la scelta provvisoria era
"ours"; esito review qui sotto. Regole applicate: deprecation-stub solo se il
canonico esiste; lowercase/kebab e path riorganizzati; contenuto più
curato/recente/completo; union deduplicata su contenuto reale da entrambi i
lati; zero righe marker nel file finale.

| File | Blocco | Decisione | Motivo |
|------|--------|-----------|--------|
| `docs/INDEX.md` | 3 | ours | Bullet regola «No Http Controllers — Folio + Actions + Filament» con link valido a `docs/wiki/rules/no-controllers-rule.md` (root repo, verificato esistente); theirs = rimozione/vuoto → il contenuto reale vince. |
| `docs/INDEX_GENERATED.md` | 1 | ours | Indice generato: entrambe le varianti case dei file esistono su fs case-sensitive (`CHANGELOG.md`+`changelog.md`, `LICENSE.md`+`license.md`, ecc. — verificato con `ls`); ours = union completa, theirs = subset deduplicato che misreporta i file reali. |
| `docs/INDEX_GENERATED.md` | 3 | ours | `CHANGELOG.md` e `changelog.md` esistono entrambi in `docs/` → union (ours). |
| `docs/INDEX_GENERATED.md` | 5 | ours | `LICENSE.md` e `license.md` esistono entrambi → union (ours). |
| `docs/INDEX_GENERATED.md` | 13 | ours | Tabella Recently Updated: union con entrambe le varianti reali su disco. |
| `docs/INDEX_GENERATED.md` | 14 | ours | Idem per le righe CHANGELOG/changelog. |
| `docs/ON-DEMAND-PATTERN.md` | 1 | ours | Annotazioni «(esiste anche INDEX.md, case-duplicate)» verificate: `wiki/{rules,skills,commands,memories}/` contengono entrambe le varianti → ours più accurato dei lati theirs (una sola variante). |
| `docs/PROJECT-STRUCTURE.md` | 3 | ours | Verificato via `ls`: `docs/architecture.md` esiste, `docs/ARCHITECTURE.md` no (è alla root del modulo, hygiene separata); la nota 2026-09-22 documenta i case-duplicates residui. Theirs puntava a file inesistente in `docs/`. |
| `docs/architecture.md` | 1 | ours | Frontmatter YAML valido + `# Rating Architecture`; theirs = solo heading senza frontmatter → ours più curato (schema wiki rispettato). |
| `docs/architecture.md` | 3 | ours | Ours = sezione completa «3. HasRatingsTrait Form Schema Pattern» (con nota sulla firma reale `?RatingsFormCallerContract`) + «4. RuleEnum»; theirs = solo «3. RuleEnum» → union = ours, nessuna perdita. |
| `docs/architecture.md` | 6 | ours | Ours: `Ultimo aggiornamento 2026-09-22` + sezione «Note storiche» che già assorbe deduplicato il contenuto del vecchio `ARCHITECTURE.md` di root (components/features/integration). Theirs: data stale 2024-02-11 + sezione «Contenuto assorbito» che duplicherebbe il body. |
| `docs/index.md` | 1 | ours | `updated: 2026-09-22` più recente di `2026-09-16`. |
| `docs/index.md` | 2 | ours | Nota «2026-09-22 — risolto» riflette lo stato post-cleanup; theirs = warning stale pre-cleanup («contengono ancora marker»). Nota: dopo questa passata il claim `grep → nessun risultato` è di nuovo vero anche dentro i fence. |
| `docs/on-demand-pattern.md` | 1 | ours | Gemello case-duplicate di `ON-DEMAND-PATTERN.md` → stessa decisione del blocco omonimo. |
| `docs/project-structure.md` | 3 | ours | Gemello case-duplicate di `PROJECT-STRUCTURE.md` → stessa decisione; il lato theirs conteneva anche marker annidato `<<<<<<<< HEAD:docs/project-structure.md` = garbage. |
| `docs/wiki/AGENTS.md` | 2 | union | Tenuti i link di entrambi i lati (`agents.md` + `AGENTS.md` + `Module Documentation`, tutti esistenti); omesso il corpo «Contenuto assorbito da `agents.md`» perché duplicato verbatim del body del file stesso (`diff AGENTS.md agents.md` = solo frontmatter). |
| `docs/wiki/INDEX.md` | 1 | ours | Link con nota «case-duplicate anche `./rules/INDEX.md` / `./skills/INDEX.md`» — entrambe le varianti esistono su disco → ours più accurato. |
| `docs/wiki/index.md` | 1 | ours | Gemello case-duplicate di `wiki/INDEX.md` → stessa decisione. |
| `docs/wiki/log.md` | 2 | ours | `## Format` top-level con spec formato + operations list; theirs proponeva `### Format` sotto Log Entries → struttura ours più curata. |
| `docs/wiki/log.md` | 3 | ours | Entries strutturate `### [data]` con dettagli e footer; theirs = righe flat precedenti. Il contenuto theirs (entry 2026-05-12) è già in ours che cita sia `index.md` sia `INDEX.md` → union = ours. |

### File extra fuori pack (marker residui dentro code fence)

Il pass precedente aveva saltato i marker dentro code fence («Skipped: 3»);
questa review li ha risolti per il requisito «zero marker nel modulo»:

| File | Posizione | Decisione | Motivo |
|------|-----------|-----------|--------|
| `docs/wiki/schema.md` | tree in fence | union | `schema.md` e `SCHEMA.md` esistono entrambi in `wiki/` → tenute entrambe le righe, rimosso garbage annidato (`<<<<<<<<`, `>>>>>>>>`, `========`). |
| `docs/wiki/SCHEMA.md` | tree in fence | union | Idem (gemello case-duplicate). |
| `docs/wiki/concepts/context-mode-rating-discipline.md` | tree in fence | union | `rules/index.md` e `rules/INDEX.md` esistono entrambi → entrambe le righe. |

### Note

- `docs/index.md` segnala come «ancora aperto» il root `README.md` con marker:
  verificato, i marker non ci sono più (grep vuoto) — il bullet è ormai stale
  ma è fuori dai blocchi di conflitto, non toccato in questa passata.
- Verifica finale: `grep -rln '^<<<<<<< \|^=======$\|^>>>>>>> \|^<<<<<<<<\|^>>>>>>>>'`
  sul modulo → nessun risultato. Le sole occorrenze residue delle stringhe
  marker sono citazioni in prosa dentro story/README (documentazione del
  cleanup), non conflitti.
- Nessun commit effettuato.
