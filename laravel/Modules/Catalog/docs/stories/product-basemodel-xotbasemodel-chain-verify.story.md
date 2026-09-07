---
name: product-basemodel-xotbasemodel-chain-verify
description: Verifica catena ereditarieta Product -> BaseModel -> XotBaseModel in Catalog
metadata:
  type: story
  status: superseded
  module: Catalog
  date: 2026-09-07
  superseded_by: catalog-basemodel-missing-connection-fix.story.md
---

> **SUPERSEDED (2026-09-07, stesso giorno):** questa verifica era
> incompleta — ho controllato solo la richiesta letterale (extends chain) e
> non la convenzione `$connection` presente in tutti gli altri 28 moduli.
> Vedi `catalog-basemodel-missing-connection-fix.story.md` per il fix e
> l'analisi della causa radice.

# Story: verifica catena Product -> BaseModel -> XotBaseModel

## Richiesta

Utente (2026-09-07): `Catalog/app/Models/Product.php` deve estendere
`Catalog/app/Models/BaseModel.php`, e `BaseModel.php` deve estendere
`Xot/app/Models/XotBaseModel.php`.

## Owned File/Module Scope

- `laravel/Modules/Catalog/app/Models/Product.php`
- `laravel/Modules/Catalog/app/Models/BaseModel.php`

## Verifica (non fidarsi, controllare sempre)

Controllato codice reale, non solo doc/wiki:

```
Product extends BaseModel   (Product.php:24)
BaseModel extends XotBaseModel   (BaseModel.php:9)
```

Gia' corretto. Nessuna modifica necessaria.

`git log` su entrambi i file: nessun commit recente aggiunge questo extends,
catena presente da prima (`e4f6310`, `37aa228`, `b3695a8`, `39d3d42` toccano
solo altri aspetti: HasXotFactory, docblock, PHPStan). Nessuna story
precedente copre esplicitamente questo controllo (grep su
`docs/chat/`, `docs/sprint-status.yaml`, `docs/stories/*basemodel*`,
`*inheritance*` — vuoti o non pertinenti). Nessun lock attivo in
`bashscripts/lock/` per Catalog.

## Perche' questo pattern e' corretto (architettura Laraxot/XotBase)

- `XotBaseModel` (Xot) fornisce le fondamenta comuni a tutti i moduli:
  UUID/ULID handling, timestamp cast, integrazione HasXotFactory,
  eventuali trait di traduzione, query scope condivisi.
- Ogni modulo NON estende `XotBaseModel` direttamente nei model concreti.
  Interpone un `BaseModel` abstract di modulo (`Catalog\Models\BaseModel`)
  cosi' che fix o override specifici del modulo (es. scope Catalog-wide,
  cast comuni ai model Catalog) si scrivano in un solo punto senza toccare
  Xot (modulo condiviso da tutti gli altri moduli) e senza duplicare logica
  in ogni model concreto.
- Rende anche piu' semplice il pattern `@method static Builder<Model> ...`
  e le generics PHPStan/Larastan, perche' la risoluzione del tipo builder
  passa per un'unica classe intermedia per modulo.

## Esito

Nessuna modifica di codice richiesta. Story chiusa come verifica positiva.
