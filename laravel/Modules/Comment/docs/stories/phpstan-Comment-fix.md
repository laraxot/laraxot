---
title: "phpstan Comment fix"
type: note
tags: [documentation]
qmd: "phpstan Comment fix"
issues: []
discussions: []
id: phpstan-Comment-fix
slug: phpstan-Comment
scope: [module:Comment, project:base_workorder_fila5]
status: Done (1 errore residuo documentato, vedi sotto)
priority: High
created: 2026-09-06
updated: 2026-09-07
---

## Problema (in gran parte risolto)
5 errori PHPStan: `isset.offset` morto su `Safe\preg_match_all` in
`ResolveCommentMentioneesAction.php` (rimosso il controllo, sempre vero per via
dello stub tipizzato), e 4 `method.deprecated` in `ListCommentsTest.php`
(`ListComments::getTable{Actions,BulkActions,Filters,Columns}()` deprecati in
Filament v5) — 3 di questi risolti spostando le assertion sulla classe dedicata
`CommentsTable` gia' esistente.

## Fix
Vedi `docs/coverage.md` per il dettaglio completo, incluso il motivo per cui
1 errore residuo (`CommentsTable::getTableActions()` ancora `method.deprecated`
per via del trait condiviso `HasXotTable`) e' stato lasciato intenzionalmente
irrisolto: richiederebbe toccare codice condiviso ad alto impatto
(`Modules/Xot/app/Filament/Traits/HasXotTable.php`), gia' in refactor da un altro
agente su un modulo diverso (Platform/AuditLogResource).

## Verifica
- PHPStan: 5 -> 1 errore (documentato, non regressione)
- PHPMD: nessuna nuova segnalazione
- Pest: 43 failed/1 passed pre-esistente (bootstrap DB `fixcity` non configurato,
  non causato da questo fix)
