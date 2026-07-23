---
title: "Metodi duplicati — UI"
type: concept
tags: [duplicate, methods]
created: 2026-07-14
updated: 2026-07-14
qmd: "duplicate-methods metodi duplicati — ui"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./04-datas.md"
  - "./advanced-form-components-1.md"
  - "./advanced-form-components.md"
  - "./agent-confidence-discipline.md"
  - "./agent-confidence-protocol.md"
  - "./agent-edit-discipline.md"
---

# Metodi duplicati — UI

Analisi sintetica dei metodi PHP con lo stesso nome all’interno di questo ambito.

- File PHP analizzati: **412**
- Metodi duplicati trovati: **34**

## Metodi duplicati

| Metodo | Occorrenze | Note |
|--------|----------|------|
| `__construct` | 15 | candidato a trait/helper |
| `getFormSchema` | 15 | candidato a trait/helper |
| `render` | 15 | candidato a trait/helper |
| `setUp` | 15 | candidato a trait/helper |
| `make` | 13 | candidato a trait/helper |
| `execute` | 6 | candidato a trait/helper |
| `definition` | 4 | candidato a trait/helper |
| `getColor` | 4 | candidato a trait/helper |
| `getIcon` | 4 | candidato a trait/helper |
| `getLabel` | 4 | candidato a trait/helper |
| `getTitle` | 4 | candidato a trait/helper |
| `mount` | 4 | candidato a trait/helper |
| `exportData` | 3 | candidato a trait/helper |
| `getDescription` | 3 | candidato a trait/helper |
| `getOptions` | 3 | candidato a trait/helper |
| `getRatioClass` | 3 | candidato a trait/helper |
| `getRatios` | 3 | candidato a trait/helper |
| `getSuggestions` | 3 | candidato a trait/helper |
| `getTableLayout` | 3 | candidato a trait/helper |
| `setTableLayout` | 3 | candidato a trait/helper |

... altri 14 metodi duplicati non elencati per sintesi.

## Riflessioni

- I duplicati con nomi generici (`__construct`, `up`, `down`, `definition`) sono spesso inevitabili, ma vanno monitorati.
- Quando un metodo compare in più classi con firme simili, conviene valutare un trait o una classe base condivisa.
- Se il metodo ha firme diverse, meglio evitare l’ereditarietà implicita e preferire un service/helper dedicato.
- Per i metodi di tipo accessor/mutator, la duplicazione è spesso legata a pattern Eloquent ricorrenti.

> Documento generato il 2026-06-15 da Claude Code.
