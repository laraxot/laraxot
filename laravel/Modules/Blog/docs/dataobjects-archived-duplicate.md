---
title: dataobjects/DataObjects archiviate — duplicati morti, usare Datas
type: decision
tags: [datas, dto, archive, duplicate, naming]
created: 2026-07-14
---

# Dataobjects/DataObjects rimosse definitivamente

Le cartole `app/dataobjects.old/` e `app/DataObjects.old/` sono state
**rimosse definitivamente** dal modulo Blog. Contenevano duplicati morti
(`ArticleData`, `ArticleImportMetrics`, `ArticleImportScores`) senza
nessun utilizzatore nel repo.

## Azione

- Eliminate `Modules/Blog/app/dataobjects.old/`
- Eliminate `Modules/Blog/app/DataObjects.old/`

## Regola

- Namespace/cartella dati: **solo `Datas`** (plurale), mai `DataObjects`,
  `Dto`, `DTOs`, `DataTransferObjects`.
- Tutte le classi estendono `Spatie\LaravelData\Data`.
- Vedi convenzione generale: `Modules/UI/docs/datas-not-dtos-convention.md`,
  `docs/chat/dtos-to-datas-cleanup-2026-07-14.md`.
