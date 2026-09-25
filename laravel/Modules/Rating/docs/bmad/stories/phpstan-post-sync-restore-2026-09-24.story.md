---
title: "Story — PHPStan dopo il sync deprecato: fatal final getFormSchema, AuditCoverage, .gitattributes (2026-09-24)"
type: story
module: Rating
epic: quality
story_id: "phpstan-post-sync-restore-2026-09-24"
status: done
qmd: "phpstan exit 255 cannot override final getFormSchema BaseRatingResource merge_remote_repo_2 gitattributes marker lfs"
related:
  - ../../../../Xot/docs/bmad/stories/phpstan-analyse-no-path-certify.story.md
  - ./auditcoverage-reinjection-2026-09-24.story.md
  - ../../../../../../docs/wiki/memories/merge-remote-repo-2-reinjects-conflict-markers.md
---
# phpstan-post-sync-restore-2026-09-24

## Perché

Richiesta utente: `cd laravel && ./vendor/bin/phpstan` e fix di tutto, parallelo, BMAD + second brain.
Il binario nudo stampa l'help: certify = `phpstan analyse` **senza path** (neon + type-coverage).

## Coordinamento

In parallelo con lo swarm Cursor (`cursor-composer`, `cursor-swarm-r1`, `cursor-swarm-ra`, story Xot
`phpstan-analyse-no-path-certify`) che teneva i lock su `RatingData`, `BaseRating`, `HasRating*`,
`BetTableAction` e ha chiuso il rebase di Rating (20:54:31). Io: solo file non lockati, dopo la fine del rebase.

## Sequenza

1. 20:48 `phpstan analyse` → exit 255: marker `<<<<<<<` in 46 file Rating (rebase lasciato da
   `merge_remote_repo_2.sh`, deprecato ma in esecuzione). Risolti dallo swarm Cursor.
2. Poi exit 255: `Cannot override final method XotBaseResource::getFormSchema()` — 2 cause reali,
   6 a cascata (IR, Progressioni). Inventario in un colpo solo con
   `bashscripts/quality-gates/class-load-fatals/find-class-load-fatals.sh` (4131 classi).
   `BaseRatingResource`/`BaseRatingMorphResource`: il merge aveva reintrodotto `public static getFormSchema()`
   (codice pre-migrazione); canon Xot = `final` d'istanza + schema in `RatingResource/Schemas/BaseRatingForm`.
   Fix: ripristino verbatim da `0d5e6f7` (tip pre-sync, diff = solo le righe reintrodotte). Fatal → 0.
3. 16 `staticMethod.notFound` in `tests/AuditCoverage/` reiniettata dal sync → `ensure-audit-coverage-gitignore.sh --fix`
   (la guardia `REMOTE:` nuova ha confermato che `laraxot/dev` di Rating la traccia di nuovo).
4. `.gitattributes` Rating con marker committati + righe `*.psd filter=lfs` (LFS vietato dallo stesso file)
   → ripristino da `0d5e6f7` (diff = 20 righe aggiunte, 0 rimosse). `git check-attr` senza warning.

## Gate

```text
./vendor/bin/phpstan analyse  (no path, output testuale) → [OK] No errors, exit 0
find-class-load-fatals.sh → files=4131 problems=0
marker <<<<<<< in Modules/Themes *.php → 0
Pest: skip ambientale (DB 10.100.200.53 irraggiungibile, vedi story Xot phpstan-modules-2026-09-24)
```

## Aperto

- Commit dei ripristini in Rating (+ `git rm` AuditCoverage) e push: senza push il remote reinietta.
- Guardia vera contro `merge_remote_repo_2.sh`: l'header "DEPRECATO" è un commento, lo script gira lo stesso.
