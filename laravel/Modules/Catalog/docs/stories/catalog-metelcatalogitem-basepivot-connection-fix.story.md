---
name: catalog-metelcatalogitem-basepivot-connection-fix
description: MetelCatalogItem->BasePivot->XotBasePivot creata, connessione mancante corretta, analisi causa radice ripetuta
metadata:
  type: story
  status: done
  module: Catalog
  date: 2026-09-07
  claimed_by: claude-sonnet-5 (this session)
  supersedes: phpstan-fix-catalog-basepivot-1788770886.story.md (vuota)
---

# Story: `MetelCatalogItem` -> `BasePivot` -> `XotBasePivot`

## Richiesta utente (2026-09-07, terza iterazione)

`MetelCatalogItem.php` deve estendere un nuovo `Catalog\Models\BasePivot.php`,
che deve estendere `Xot\Models\XotBasePivot.php`. Poi correzione: la nuova
`BasePivot.php` mancava di `$connection = 'catalog'` — stesso errore della
story precedente sul `BaseModel`.

## Owned File/Module Scope

- `laravel/Modules/Catalog/app/Models/BasePivot.php`
- `laravel/Modules/Catalog/app/Models/MetelCatalogItem.php`
- `laravel/Modules/Catalog/docs/coverage.md`

## Discussione della premessa (obbligatoria, standing order pillar 3)

Prima di implementare ho controllato la migration
`2026_07_13_100001_create_metel_catalog_items_table.php`: 8 colonne di
business data, `id` autoincrement, relazione `MetelCatalog::items()` e'
`hasMany` non `belongsToMany`, `product_id` nullable, nessun
`->using(MetelCatalogItem::class)` in tutto il modulo. I pivot reali del
progetto (es. `Blog\Models\CategoryPost`, migration
`2023_11_23_000005_create_category_post_table.php`) hanno solo le 2 FK +
timestamps. Ho segnalato la discrepanza all'utente via domanda diretta.
**L'utente ha scelto esplicitamente di procedere comunque con
`BasePivot`/`XotBasePivot`** — implementato su questa base, non ho deviato
unilateralmente.

## Controllo altri agenti (obbligatorio, non fidarsi)

Durante l'esecuzione di QUESTA story ho osservato in diretta 2 edit
concorrenti sullo stesso file da altri agenti/sessioni, nonostante avessi
gia' preso lock (`bashscripts/lock/`) su `MetelCatalogItem.php`:

1. Commit `c13b63c` (un altro agente, timestamp 10:48:06): ha creato
   `BasePivot.php` SENZA `$connection` — stesso errore di
   `catalog-basemodel-missing-connection-fix.story.md`, non ha imparato
   dalla story gia' scritta poche righe sotto nello stesso `docs/stories/`.
   Story vuota lasciata: `phpstan-fix-catalog-basepivot-1788770886.story.md`
   (0 byte, questa story la sostituisce).
2. Un terzo edit (agente/sessione non identificata) ha cambiato
   `MetelCatalogItem` da `extends BasePivot` a `extends BaseModel` —
   convergendo indipendentemente sulla stessa obiezione che avevo gia'
   sollevato e su cui l'utente aveva gia' deciso "procedi con BasePivot".
   Ripristinato `extends BasePivot` per rispettare la decisione esplicita
   dell'utente, che prevale su un edit concorrente non richiesto.

Questo conferma (ancora) la memoria second brain "Shared filesystem,
concurrent sessions": piu' sessioni condividono lo stesso working tree, il
lock file non e' universalmente rispettato da tutti gli agenti.

## Fix applicato

`app/Models/BasePivot.php`:
```php
abstract class BasePivot extends XotBasePivot
{
    /**
     * The connection name for the model.
     */
    protected $connection = 'catalog';
}
```

`app/Models/MetelCatalogItem.php`: `extends BasePivot` (invece di
`XotBaseModel` diretto o `BaseModel`).

## Verifica

- Lock preso su entrambi i file prima di ogni edit, rilasciato a fine task.
- `phpstan analyse Modules/Catalog` (cache pulita, dopo il ripristino
  finale): 0 errori. (Un run intermedio ha mostrato 10 errori
  `staticMethod.notFound` su `::factory()` di CatalogModel/CatalogPartType/
  CatalogMeasureUnit/CatalogFrequency/Product/MetelCatalog — pre-esistenti,
  causati dalla rimozione di `use HasXotFactory` nel commit `39d3d42`, non
  da questo fix; vedi second brain
  `project_hasxotfactory_missing_static_factory_method`. Il run finale
  post-ripristino e' tornato pulito.)
- PHPMD (`Modules/Xot/phpmd.ruleset.xml`) su entrambi i file: 0 violazioni.
- Pint: passed.

## Esito

Fix committato e pushato su `laraxot/dev` (repo del modulo Catalog).
`docs/coverage.md` aggiornato con nota per l'owner: la semantica
pivot-vs-model per `MetelCatalogItem` resta un compromesso — implementato
come richiesto, ma la struttura tabella suggerisce `BaseModel`. Da rivedere
se causa attrito futuro.
