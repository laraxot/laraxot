---
id: metelcatalogitem-basemodel-not-pivot
slug: metelcatalogitem-basemodel-not-pivot
scope: [module:Catalog, project:base_workorder_fila5]
status: superseded
superseded_by: catalog-metelcatalogitem-basepivot-connection-fix.story.md
priority: High
created: 2026-09-07
related:
  - phpstan-fix-catalog-basepivot-1788770886.story.md
  - product-basemodel-xotbasemodel-chain-verify.story.md
  - ../../Xot/docs/stories/18.2.2.redundant-hasxotfactory-trait-usage-cleanup.story.md
---

## SUPERSEDED 2026-09-07

The user gave contradictory answers across two sibling sessions on the same
question ("BaseModel" here, "BasePivot" in the other session). Asked again in
THIS session to settle it: **final answer is BasePivot** — see
`catalog-metelcatalogitem-basepivot-connection-fix.story.md`, which is what's
actually committed. Kept this story per no-delete policy; everything below
this point reflects the superseded BaseModel conclusion, not the final state.

## Problema

Utente ha chiesto di far estendere `MetelCatalogItem` da
`Catalog\Models\BasePivot` → `Xot\Models\XotBasePivot`, per coerenza con la
regola generale "modello dovrebbe passare per la classe Base del proprio
modulo prima di arrivare a Xot".

## Investigazione (prima di eseguire, come richiesto: "capisci da solo il perche'")

Verificato nel codice reale, non assunto dal nome del file:

- `MetelCatalog::items()` e' `hasMany(MetelCatalogItem::class)`, non
  `belongsToMany()->using()`. Nessuna relazione many-to-many nel modulo usa
  questa classe come pivot.
- `MetelCatalogItem` ha una `id` auto-increment reale, una
  `MetelCatalogItemFactory` dedicata, un seeder, e un `RelationManager`
  Filament proprio (risorsa di prima classe, non riga di giunzione).
- Colonne di business (`gross_price`, `net_price`, `barcode`, `etim_code`,
  `description`, ...) ben oltre le due FK che avrebbe una pivot.

Conclusione: non e' una pivot Eloquent nel modo in cui e' usata oggi.
Convertirla a `XotBasePivot` cambierebbe `keyType` a string e semantica
Pivot senza un `belongsToMany` reale dietro — rischio di rottura silenziosa
(chiavi, cast, aspettative del `RelationManager`).

## Decisione (utente, interattivo, questa sessione)

Chiesto esplicitamente all'utente se procedere comunque con `BasePivot` o
tenere `BaseModel`. Risposta: **tenere `BaseModel`** (stesso pattern di
`Product`, `CatalogFrequency`, ecc.).

## Fix

`MetelCatalogItem extends BaseModel` (era `XotBaseModel` diretto prima di
questa sessione, poi cambiato in `BasePivot` da un'altra sessione con lock
attivo `catalog-basepivot-fix` — vedi nota di coordinamento
`docs/chat/2026-09-07-metelcatalogitem-not-a-pivot-user-decision.md` per la
cronologia del conflitto). Import `XotBaseModel` rimosso (non piu' usato).

## Verifica

`./vendor/bin/phpstan analyse Modules/Catalog --memory-limit=-1` → 0 errori
(sia con `BaseModel` che con `BasePivot` il tipo risolve pulito: e' una
decisione di modellazione, non un errore di tipo — PHPStan non la puo'
arbitrare da solo).

## Nota per chi coordina

`Modules/Catalog/app/Models/BasePivot.php` (la classe in se') resta valida
come infrastruttura per una futura vera pivot in questo modulo — non
rimossa, solo non usata da `MetelCatalogItem`.
