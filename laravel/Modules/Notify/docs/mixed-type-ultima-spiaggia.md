---
title: "Mixed (tipo di dato) - Solo come Ultima Spiaggia"
type: concept
tags: [mixed, type, ultima, spiaggia]
created: 2026-07-14
updated: 2026-07-14
qmd: "mixed-type-ultima-spiaggia mixed (tipo di dato) - solo come ultima spiaggia"
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

# Mixed (tipo di dato) - Solo come Ultima Spiaggia

**Regola critica**: Il tipo `mixed` in PHP deve essere usato **SOLO come ultima spiaggia**.

## Preferenze (in ordine)

1. **Union types** - `string|int|null`
2. **Generics** - `Collection<int, User>`, `array<string, Component>`
3. **Interfacce** - `ArrayAccess`, `Iterator`
4. **Classi base** - `object`, `array`
5. **mixed** - solo quando non esiste alternativa (es. API esterne senza tipo garantito)

## Collegamenti

- [array-keys-mixed-property-exists.mdc](../.cursor/rules/array-keys-mixed-property-exists.mdc)
- [filament-array-typing.mdc](../.cursor/rules/filament-array-typing.mdc)
- [critical-rules-and-memories](../laravel/Modules/Xot/docs/critical-rules-and-memories.md)
- [AGENTS.md](../AGENTS.md)
