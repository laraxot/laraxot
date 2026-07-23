---
title: "Task: Migliorare LocationSelector Type Safety - UI"
type: concept
tags: [task, location, selector]
created: 2026-07-14
updated: 2026-07-14
qmd: "task-location-selector task: migliorare locationselector type safety - ui"
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

# Task: Migliorare LocationSelector Type Safety - UI

**Modulo**: UI
**Priorita'**: Media
**Completamento**: 30%

---

## Descrizione

LocationSelector e InteractiveMap hanno 24 suppressioni combinate. Questi componenti gestiscono dati geografici con tipi mixed.

## Azioni

- [ ] Definire LocationData DTO per coordinate
- [ ] Tipizzare parametri lat/lng come float
- [ ] Usare Geo module DTOs per consistenza
- [ ] Rifattorizzare InteractiveMap con tipi espliciti

## Criteri di Completamento

- [ ] LocationSelector: max 2 suppressioni
- [ ] InteractiveMap: max 2 suppressioni
- [ ] Funzionalita' mappa preservata
