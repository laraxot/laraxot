---
title: "Rules Index"
type: index
created: 2026-05-11
updated: 2026-06-06
tags: [rules, index, on-demand]
related:
  - ../rules/00-TRIGGER_MAP.md
  - ../rules/on-demand-pattern.md
---

# Rules Index

Le Rules progettuali vivono qui, nel wiki del Module **Comment**, e vengono caricate **on-demand**.

> Vedi anche → [Trigger Map](../rules/00-TRIGGER_MAP.md)

## Regola

1. individua il trigger del task
2. consulta `../rules/00-TRIGGER_MAP.md`
3. se serve, esegui `qmd search "<topic>"`
4. leggi solo la Rules wiki pertinente

## Pattern di caricamento

| Pattern | Comando |
|---------|---------|
| Carica Rules specifica | `Read ../rules/<name>.md` |
| Ricerca semantica | `qmd search "<topic>"` |
| Via trigger map | Consulta `../rules/00-TRIGGER_MAP.md` |

## Rules modulo

| Rule | File |
|------|------|
| Provider manifest (`module.json` + `composer.json`) | [module-providers-manifest.md](module-providers-manifest.md) |
| Service Provider Registration (no manual register) | [module-service-provider-registration.md](module-service-provider-registration.md) |

## Note

- La sorgente di verita' per le Rules e' sempre il wiki locale
- Non embeddare Rules nei prompt di avvio
- Per Rules globali, consulta il [wiki root](../../docs/wiki/rules/INDEX.md)

## Aggiungere una Nuova RULES

1. Crea `../rules/<nome>.md` con contenuto completo
2. Aggiungi la voce in `../rules/00-TRIGGER_MAP.md`
3. Aggiorna questo indice se la Rules e' ricorrente
4. Committa: `docs: add rules <nome>`

- [no-livewire-filament-widgets-only](./no-livewire-filament-widgets-only.md)
- [phpstan-module-scoped-analysis](./phpstan-module-scoped-analysis.md)
- [filament-resource-schema-triad.md](filament-resource-schema-triad.md) — triade Form/Infolist/Table CommentResource
