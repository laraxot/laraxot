---
title: "BaseModel: Regola obbligatoria e motivazione"
type: concept
tags: [basemodel]
created: 2026-07-14
updated: 2026-07-14
qmd: "basemodel basemodel: regola obbligatoria e motivazione"
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

# BaseModel: Regola obbligatoria e motivazione

## Regola
**Tutti i model del modulo devono estendere BaseModel (già presente nel namespace), mai direttamente Model.**

## Motivazione
- Permette di centralizzare override, comportamenti comuni, trait, policy, logica multi-tenant e metodi utility.
- Migliora la manutenibilità: ogni modifica globale si fa in un solo punto.
- Evita errori e duplicazione di codice.
- Rende il codice più leggibile e conforme alle regole di progetto.

## Convenzione
- BaseModel deve essere già presente nel namespace (es: `Modules\Notify\Models\BaseModel`).
- Non serve alcun `use` per BaseModel nei model dello stesso namespace.
- Estendere direttamente Model è un errore bloccante e va evitato in tutte le PR.

## Esempio corretto
```php
namespace Modules\Notify\Models;

class NotificationTemplate extends BaseModel {}
```

## Collegamenti
- [../module_notify.md](../module_notify.md)
- [../../Patient/docs/basemodel.md](../../patient/docs/basemodel.md)
- Regola "evita override inutili" nei prompt e docs root.

---
**Nota:** Se trovi un model che estende Model invece di BaseModel, correggi subito e segnala l’errore nella PR.
