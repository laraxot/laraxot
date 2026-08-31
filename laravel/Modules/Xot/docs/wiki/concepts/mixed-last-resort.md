---
title: mixed type last resort
description: Policy Laraxot su mixed — vietato salvo ultima spiaggia documentata (ADR-011).
document_type: concept
module: Xot
status: active
language: it-IT
updated_at: 2026-08-19
related:
  - ../../../../../../bmad-output/architecture.md
  - ../../../../../../bashscripts/ai/wiki/rules/no-mixed-types-pattern.md
  - ../../../../../../docs/chat/narrow-mixed-remaining-properties.md
tags: [phpstan, mixed, types, quality]
---

# `mixed` — ultima spiaggia (ADR-011)

## Regola

**Non usare `mixed`** in PHPDoc, return type o property se un tipo più stretto è derivabile.

Ordine di escalation:

1. Tipo nativo / union esplicita
2. Generic con bound (`@template T of Model`)
3. `Webmozart\Assert` o Actions cast Xot (`SafeIntCastAction`, …)
4. **`mixed` + WHY** in story/docs modulo (tabella legacy, produttore non dimostrabile)

## Campagna 2026-08

`@property mixed` nudi: **38 → 5** — i 5 residui sono documentati come non restringibili o codice morto ([handoff](../../../../../../docs/chat/narrow-mixed-remaining-properties.md)).

## Anti-pattern

- `@return T|mixed` su helper config — PHPStan normalizza a `mixed`
- `@return mixed` su metodo con zero call site — preferire rimozione o tipo reale di `Config::get()`

## Collegamenti

- [no-mixed-types-pattern.md](../../../../../../bashscripts/ai/wiki/rules/no-mixed-types-pattern.md)
- [architecture ADR-011](../../../../../../bmad-output/architecture.md)
