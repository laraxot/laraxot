---
title: "Rating docs: ingressi verificati contro il codice"
type: story
status: done
created: 2026-10-14
updated: 2026-10-14
module: Rating
tags: [docs, rating, verifica, indici, link]
qmd: "rating docs ingressi readme 00-index verifica codice fixcity valutazione cittadino link rotti stale"
related:
  - ../../../Fixcity/docs/stories/dcp-56-organizzazione-docs-moduli-e-temi.story.md
---

# Rating docs: ingressi verificati contro il codice

Fase BMAD: documentazione e qualita' (DOC-010 e DOC-002 del piano `bmad-output/stories/docs-organization/`).

## Richiesta
Studiare il codice del modulo Rating, correggere `README.md`, `00-INDEX.md`, `index.md` con soli fatti verificati, ridurre i link rotti negli ingressi e segnare i documenti falsi senza cancellarli.

## Analisi
- Il README dichiarava il modulo "usato dalle schede HR (Performance, IndennitaResponsabilita)". Il modulo IndennitaResponsabilita non esiste in `laravel/Modules`. L'uso reale e' Fixcity (`SubmitCitizenTicketRatingAction`, `GetCitizenRatingAggregateAction`, concern `InteractsWithTicketCitizenRating`) e Blog (7 file importano classi Rating).
- `routes/web.php` e `routes/api.php` sono vuoti. `php artisan route:list --json` (461 rotte totali) mostra 11 rotte del modulo, tutte del pannello Filament `rating/admin`.
- Le viste `livewire/favorite*`, `rate/*`, `rate_it` non hanno classi Livewire e nessun file del progetto le richiama con `rating::`.
- `00-INDEX.md` aveva titolo con emoji, tabelle con nomi file senza link (`PRD.md` non esiste, esiste `prd.md`), un link a una story 4.26 inesistente e la data "2026-03-24".
- Il punto "root README con marker di merge" di `index.md` e' chiuso: `grep -nE '^<<<<<<<|^=======$|^>>>>>>>' laravel/Modules/Rating/README.md` non trova nulla.
- 61 stub della cartella hanno `canonical: ../../../Themes/docs/shared-components/...`: quel percorso non esiste, la cartella reale e' `Themes/Barthelemy/docs/shared-components/`.

## Modifiche
- `README.md`: identita', tabella di cio' che il codice contiene, consumatori reali, limiti, quick start.
- `00-INDEX.md`: dichiarato canonico; stato verificato; ruolo delle directory (canoniche, evidence, legacy); link a file esistenti.
- `index.md`: nota di canonicita', punto sul README root chiuso con la prova.
- Segnati `stale` con nota datata (contenuto non riscritto): `INDEX.md` (statistiche di luglio), `00-index.md` (copia), `rating-architecture.md` e `up.md` (riguardano IndennitaResponsabilita).
- Link rotti riscritti: `bmad/INDEX.md` (9), `bmad/index.md` (2), `bmad/readme.md` (27) riportati alla radice di `docs/` solo dove il bersaglio esiste; link senza bersaglio resi testo con la dicitura "riferimento non presente nel repository" in `bmad/00-INDEX.md`, `bmad/README.md`, `bmad/readme.md`, `graphify/README.md`, `wiki/INDEX.md`, `wiki/index.md`.
- Front matter: `frontmatter --fix --default-status draft` sui file rimasti incompleti: 52 corretti; 6 `manual-stray-block` restano (frammenti "Merged from ... collided with this file": contenuto intenzionale, non toccato).

## Verifica
```
audit_one (criterio dell'audit, file indice a profondita' 0 e 1)   link rotti: 59 -> 0
docs-organizer links --by-name, busy-minutes 0                      eventi unresolved: 667 -> 608 (tutta la cartella)
stessi eventi sui 16 file toccati                                   62 -> 3
php artisan route:list --json                                       rotte rating: 11 (tutte rating/admin)
DB_DATABASE_LIMESURVEY= bash bashscripts/tools/pest-scratch-db.sh Modules/Rating/tests
                                                                    Tests: 182 failed (0 assertions), tutti SQLiteDatabaseDoesNotExistException
```
I 3 `unresolved` rimasti sui file toccati sono in documenti segnati `stale`.

## Decisioni
- Gli ingressi dichiarano l'indice canonico `00-INDEX.md` (contratto docs); nessun indice con nome alternativo e' stato creato.
- I documenti falsi si segnano, non si cancellano.

## Aperto
- La suite Pest non e' una prova di regressione: serve un database SQLite o la configurazione di test del modulo (memoria `rating-pest-suite-pre-existing-breakage.md`: 145 passati, 23 falliti).
- 56 collisioni di maiuscole e 65 gruppi di nomi quasi uguali nella radice di `docs/`: DOC-004 e DOC-006, richiedono revisione umana.
- Il `decision-log.md` e' vuoto.
- Gli stub con `canonical` verso `Themes/docs/shared-components` non sono riparati (vedi nota di memoria 2026-10-14).
