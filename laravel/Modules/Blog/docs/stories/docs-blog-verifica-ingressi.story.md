---
title: "Blog docs: ingressi verificati contro il codice"
type: story
status: done
created: 2026-10-14
updated: 2026-10-14
module: Blog
tags: [docs, blog, verifica, indici, link, legacy]
qmd: "blog docs ingressi readme 00-index verifica codice twentyone cms predict resources_to_delete transaction link rotti stale"
related:
  - ../../../Fixcity/docs/stories/dcp-56-organizzazione-docs-moduli-e-temi.story.md
---

# Blog docs: ingressi verificati contro il codice

Fase BMAD: documentazione e qualita' (DOC-010 e DOC-002 del piano `bmad-output/stories/docs-organization/`).

## Richiesta
Studiare il modulo Blog, correggere `README.md`, `00-INDEX.md` e gli indici, ridurre i link rotti, segnare i documenti falsi.

## Analisi
- Il README diceva "Core module for the FixCity Platform": Fixcity non importa Blog (solo `morph_map.php` elenca `Article` e `Banner`). Lo usano Cms (`xra.php`) e il tema TwentyOne; il tema Sixteen di Fixcity ha solo stub `pages_old/*.stub`.
- Rotte: `routes/` vuoto, 23 rotte Filament `blog/admin`.
- `app/` contiene 332 file, 112 in `app/resources_to_delete/` (anche viste `play_money_markets`), `app/config/config.php` e `app/Config/config.php` identici, `app/lang/` accanto a `lang/`, `Article.bak`.
- `00-INDEX.md` presentava `ai.md` come "Integrazione AI" e `comment.md` come "Integrazione sistema commenti": sono elenchi di URL. `visual_editor.md`, l'hub di audit e il modulo `Predict` non esistono.
- `pages.md` descrive la gestione pagine: Blog non ha `PageResource` e la migrazione `create_blog_pages_table` e' `.to_cms`.
- `models/transaction-removal.md` dice "Completato" ma `app/Models/Transaction.php` esiste e la migrazione `2024_05_02_185212_create_transactions_table.php` e' attiva.
- `index.md` e `INDEX.md` sono stub vuoti (solo un commento HTML).
- Test: 1 file, 1 test.

## Modifiche
- `README.md` riscritto; `00-INDEX.md` riscritto come indice canonico (stato verificato, ruolo delle directory, documenti stale, elenchi di URL dichiarati tali).
- Segnati con nota datata: `pages.md` e `models/transaction-removal.md` (`stale`), `index.md` e `INDEX.md` (`stub`), `00-index.md` (`stale`).
- `wiki/index.md`: 8 link senza bersaglio resi testo "riferimento non presente nel repository".
- Front matter: 22 file corretti con `frontmatter --fix --default-status draft` (56 righe spurie rimosse).

## Verifica
```
audit_one (file indice a profondita' 0 e 1)       link rotti: 16 -> 2
   residui: raw/README.md:40-41 (raw/ immutabile); i link di 00-index.md (copia stale) sono stati resi testo
docs-organizer links --by-name, busy-minutes 0    unresolved: 348 -> 334 (tutta la cartella)
stessi eventi sui 9 file toccati                 18 -> 4
php artisan route:list --name=blog                Showing [23] routes
Pest su DB usa-e-getta                            Tests: 1 passed (1 assertions)
```

## Decisioni
- Un solo indice canonico (`00-INDEX.md`); gli stub vuoti sono marcati, non riempiti con un secondo indice.
- Il codice non e' stato toccato: `resources_to_delete/`, `Article.bak`, duplicati di `config` e `lang` sono segnalati.

## Aperto
- Decidere il destino di `app/resources_to_delete/`, di `Transaction` e di `Article.bak`.
- Il `decision-log.md` ha ancora `github_issue: #XXX`.
- Molti documenti (`multi-database-architecture.md`, `parental-sti-architecture.md`) non sono stati riverificati.
- 25 collisioni di maiuscole nella radice (DOC-004, DOC-006).
