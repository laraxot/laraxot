---
title: "Comment docs: ingressi verificati contro il codice"
type: story
status: done
created: 2026-10-14
updated: 2026-10-14
module: Comment
tags: [docs, comment, verifica, indici, spatie, link]
qmd: "comment docs ingressi readme 00-index verifica codice internalizzazione spatie packages ticket link rotti stale"
related:
  - ../../../Fixcity/docs/stories/dcp-56-organizzazione-docs-moduli-e-temi.story.md
---

# Comment docs: ingressi verificati contro il codice

Fase BMAD: documentazione e qualita' (DOC-010 e DOC-002 del piano `bmad-output/stories/docs-organization/`).

## Richiesta
Studiare il modulo Comment, correggere `README.md`, `00-INDEX.md`, `index.md` con fatti verificati, ridurre i link rotti, segnare i documenti falsi.

## Analisi
- `AUDIT-MIGRATION-STATUS.md`, `audit-migration-status.md` e `STORY-158-COMPLETION-SUMMARY.md` dichiarano l'internalizzazione di Spatie completata e `packages/spatie/` eliminata. Il 2026-10-14 `laravel/Modules/Comment/packages/spatie/` esiste (tracciata in git, ultimo commit 2026-09-28), `vendor/composer/autoload_psr4.php` risolve `Spatie\Comments\` verso quella cartella e `app/Actions/SanitizeCommentTextAction.php` importa `Spatie\Comments\Support\CommentSanitizer`.
- `ticket-comments.md` descrive il `Ticket` con il trait Spatie; il codice (`Modules/Fixcity/app/Models/Ticket.php`) usa `Modules\Comment\Models\Concerns\HasComments` e implementa `Commentable`.
- `native-comments-engine.md` ha uno stato fermo al 2026-06-06 (cita `app/Support/CommentConfig.php`, che non esiste) e una rotta `comment::assets.styles` che non esiste.
- `module.json` dice `"enabled": false`; `modules_statuses.json` (radice e tenant) dice `Comment: true`; il modulo e' usato da Fixcity (`Ticket`, `User`) e Blog (`Article`, tema TwentyOne). `requires` cita un modulo `Notification` che non esiste (c'e' `Notify`).
- Il vecchio `00-INDEX.md` indicava come hub un file in `raw/` e un modulo `Predict` inesistente.
- Rotte: `routes/` vuoto, 6 rotte Filament `comment/admin`.

## Modifiche
- `README.md`: riscritto (identita', cosa contiene, consumatori, limiti, quick start); il testo precedente aveva badge rotti, emoji e un blocco di front matter spurio in coda.
- `00-INDEX.md`: dichiarato canonico, con stato verificato, ruolo delle directory, documenti stale.
- `index.md`: nota di canonicita', `status: active`.
- Segnati `stale` (nota datata, contenuto non riscritto): `AUDIT-MIGRATION-STATUS.md`, `audit-migration-status.md`, `STORY-158-COMPLETION-SUMMARY.md`, `ticket-comments.md`, `INDEX.md` (copia di `index.md`), `00-index.md` (copia).
- `native-comments-engine.md`: aggiunta una nota di verifica datata sotto "Stato implementazione", senza toccare il resto.
- `wiki/00-INDEX.md`, `wiki/00-index.md`, `wiki/index.md`: link senza bersaglio resi testo con la dicitura "riferimento non presente nel repository".
- Front matter: 40 file corretti con `frontmatter --fix --default-status draft` (48 righe spurie rimosse).

## Verifica
```
audit_one (file indice a profondita' 0 e 1)       link rotti: 32 -> 2
   residui: raw/README.md:40-41 (raw/ immutabile); i link di 00-index.md (copia stale) sono stati resi testo
docs-organizer links --by-name, busy-minutes 0    unresolved: 225 -> 195 (tutta la cartella)
stessi eventi sui 13 file toccati                 31 -> 1
php artisan route:list --name=comment             Showing [6] routes
Pest su DB usa-e-getta                            Tests: 41 failed, 3 passed, SQLiteDatabaseDoesNotExistException
```

## Decisioni
- L'indice canonico e' `00-INDEX.md`; `index.md` resta l'elenco per argomento.
- I documenti che affermano il falso vengono segnati, non riscritti: la decisione su cosa fare di `packages/spatie/` e' del proprietario del modulo.

## Aperto
- Chiudere la fase 4 (rimuovere `packages/spatie/` o dichiararla dipendenza permanente) e correggere `SanitizeCommentTextAction`.
- Allineare `module.json` (`enabled`, `requires`) e `modules_statuses.json`.
- Stabilire se le viste `folio-moderation/` sono montate (non verificato).
- `wiki/log.md.lock` e' del 2026-09-25 (task `phpstan-comment-finalize`): lock scaduto, la voce di log e' stata aggiunta comunque.
- 25 collisioni di maiuscole nella radice (DOC-004, DOC-006).
