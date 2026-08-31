---
title: "GetResourceClassNameByModelClassAction — fallback panel-aware di XotBaseResource"
module: "Xot"
type: concept
status: approved
tags: [xot, filament, actions, resource, form, table]
created: 2026-08-18
updated: 2026-08-18
qmd: "GetResourceClassNameByModelClassAction resource canonica getFormClass getTableClass fallback filament panel"
related:
  - "./actions-over-services.md"
  - "./filament/xotbaseresource.md"
  - "./stories/5.7.phpstan-modules-green.story.md"
---

# `GetResourceClassNameByModelClassAction` — call site in `XotBaseResource`

## Perché esiste (il Job)

`form()` è `final` e carica `{Resource}\Schemas\{Model}Form`. Il FQCN parte da `static::class`.

`Performance\IndividualeResource` estende `Ptv\BaseSchedaResource`. Chi apre una scheda individuale è il valutatore: se `static::class` non ospita `Schemas\{Model}Form`, senza fallback `form()` esplode. Soft-skip (schema vuoto) sarebbe peggio: pagina bianca silenziosa.

L'Action chiede al **pannello** quale Resource è registrata per quel model (`Filament::getModelResource()`), poi si ricompone `\Schemas\{Model}Form` / `\Tables\{Plural}Table` / `\Schemas\{Model}Infolist`.

Call site: `XotBaseResource::getFormClass()`, `getTableClass()`, `getInfolistClass()`.
Se la nested locale (`static::class\Schemas\...`) manca, l'Action risolve la Resource del pannello
e si ricompone lo stesso suffisso.

## Contratto

| Voce | Valore |
|---|---|
| FQCN | `Modules\Xot\Actions\Filament\GetResourceClassNameByModelClassAction` |
| Input | `class-string<Model>` |
| Output | `class-string<XotBaseResource>` |
| Errore | `LogicException` se il pannello non ha Resource per quel model |
| Pattern | Spatie `QueueableAction` |

Non è un autoloader: restituisce la Resource, non la Form.

## Uso (nel Resource, non a mano)

Prima la nested locale. Se manca, Resource canonica del model. Se manca anche quella → `LogicException` (niente form vuoto).

Fuori da un pannello Filament (`artisan`, job) l'Action fallisce di proposito: non inventare un FQCN.

## Errore da non ripetere

**Sbaglio:** vedere l'Action senza call site (o un import “morto”) e *rimuovere* il fallback, documentando «non cablata, scelta di prodotto».

**Perché era sbagliato:** il call site *è* il prodotto. PHPStan `class.notFound` sull'Action significava «scrivi la classe», non «cancella chi la chiama». Documentare lo stato del working tree come SSoT ha congelato un buco: i consumer Ptv/Performance/IR.

**Regola:** se un'Action esiste per un call site in `XotBaseResource`, il fallback si ripristina. Non si “pulisce” il Resource per far coincidere docs e codice rotto. Un import morto si cablà o si motiva con un Job che *non* riusa model tra moduli — qui quel Job non esiste.

## Riferimenti

- [Contratto XotBaseResource](./filament/xotbaseresource.md)
- [Story 5.7](./stories/5.7.phpstan-modules-green.story.md)
