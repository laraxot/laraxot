---
title: "XotBaseRelationManager — derivazione lazy della Resource"
type: concept
status: canonical
module: Xot
created: 2026-09-10
updated: 2026-09-10
tags: [filament, relation-manager, typed-property, gotcha, xotbase]
qmd: "xotbaserelationmanager resource typed static property must not be accessed before initialization trans getResourceClass namespace derivation livewire"
related:
  - ../../filament/relation-manager-guidelines.md
---

# `XotBaseRelationManager::$resource` è derivata dal namespace, non dichiarata

## Design corrente

Un RelationManager che estende `XotBaseRelationManager` **non deve** dichiarare
`protected static string $resource = XxxResource::class;`. La Resource genitrice
si ricava dal namespace del RM:

```
Modules\{Module}\Filament\Resources\{Name}Resource\RelationManagers\{This}
                                    └──────────── $resource ────────────┘
```

`getResourceClass()` (statica, `protected`) fa questo parsing una volta e memoizza
in `static::$resource`. `getResource()` (di istanza, per compat Filament) e
`trans()` (statica) la richiamano entrambe.

## La trappola: `Typed static property … must not be accessed before initialization`

`protected static string $resource;` è **tipata senza default**. Chi legge
`static::$resource` direttamente prima che `getResourceClass()` l'abbia
inizializzata prende un fatale PHP.

Successo reale (2026-09-10): `XotBaseRelationManager::trans()` faceva
`return static::$resource::trans(...)`. `trans()` è chiamata **eagerly** dentro
`getTableHeaderActions()` (`->tooltip(static::trans('actions.create.tooltip'))`)
durante un ciclo Livewire `update`, prima che qualcosa chiamasse `getResource()`.
Le proprietà statiche non sopravvivono tra request: ogni POST Livewire ripartiva
con `static::$resource` non inizializzata → 500 sulla pagina di edit utente
(tab per assegnare ruoli/permessi).

## Regola

- **Mai** leggere `static::$resource` grezza. Passare sempre da
  `static::getResourceClass()` (o `$this->getResource()`), che inizializza al volo.
- Un RM figlio può comunque forzare la Resource dichiarando
  `protected static string $resource = XxxResource::class;` con un valore — utile
  solo se il namespace non segue lo schema sopra.
- Vale anche per ogni nuovo metodo statico del base che deve conoscere la Resource.
