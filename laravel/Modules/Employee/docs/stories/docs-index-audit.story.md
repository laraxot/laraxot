---
title: "Employee docs — audit indice documentazione"
type: story
module: Employee
story_id: docs-index-audit
slug: docs-index-audit
status: done
created: 2026-09-03
---

# Employee docs — audit indice documentazione

Come maintainer del modulo Employee, voglio un `docs/index.md` che collega tutti i 293 file `.md` per argomento, così da orientarmi senza doverli aprire uno a uno.

Fatto: creato `docs/index.md` con sezioni per argomento (panoramica, architettura, business logic, feature, sviluppo per dominio, implementazione, manutenzione/PHPStan, modelli, pagine, widget, testing, analisi, refactoring, story, wiki interno) e sezione "Storico / da consolidare" per i 66 gruppi di duplicati esatti individuati via `md5sum` (incluso l'intero albero `things-to-develop/`, i doppioni root↔sottocartella, le varianti case-only in `models/` e `wiki/*/INDEX.md`) e per i cluster a contenuto divergente (`business_logic/` vs `business_logic_new/`, `xotbase_extension_rules.md` root vs `architecture/`).

Nessun file `.md` esistente è stato spostato, rinominato o cancellato. `INDEX.md` (preesistente, maiuscolo) resta invariato e citato come riferimento rapido.

Verifica: ogni link in `index.md` risolto contro il filesystem (`comm`/loop bash), un solo "missing" atteso essendo il link a questa stessa story creata subito dopo.
