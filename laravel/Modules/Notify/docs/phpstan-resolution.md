---
title: "Risoluzione conflitto SmsService.php"
type: concept
tags: [phpstan, resolution]
created: 2026-07-14
updated: 2026-07-14
qmd: "phpstan-resolution risoluzione conflitto smsservice.php"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index-2.md"
  - "./00-index.md"
  - "./absolute-completion-100.md"
  - "./acronym-naming-conventions-1.md"
  - "./acronym-naming-conventions-2.md"
  - "./acronym-naming-conventions.md"
  - "./action-plan-immediate.md"
---

# Risoluzione conflitto SmsService.php

## Motivazione
Il conflitto è stato risolto mantenendo:
- Una sola dichiarazione per ogni variabile pubblica
- Documentazione in italiano per chiarezza
- Factory method `make()` come alias di `getInstance()`
- Costruzione dinamica del driver SMS tramite backslash singolo, in linea con PSR-12

## Impatto
La soluzione garantisce compatibilità con l'architettura a servizi, estendibilità e coerenza di stile.

## Collegamento alla doc root
Vedi `/docs/notify_conflict_links.md` per la mappatura dei file documentati localmente e i riferimenti incrociati.
