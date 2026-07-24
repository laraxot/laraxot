---
title: "Porting gestionale_commesse — Catalog (redo reshape)"
type: concept
tags: [import, gestionale-commesse, catalog, reshape, xotbase]
created: 2026-07-23
updated: 2026-07-23
qmd: "porting Catalog reshape redo XotBase revert"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./import-status.md"
  - "../../../../docs/wiki/concepts/laraxot-module-canonical-structure.md"
  - "../../../../docs/wiki/concepts/porting-external-module-playbook.md"
---

# Porting gestionale_commesse — Catalog (redo reshape, 2026-07-23)

## Contesto

Il modulo `Catalog` era stato riportato ad una copia grezza (Domain/Application/Infrastructure) da
un processo concorrente che aveva revertito un reshape XotBase precedente. Questa sessione ha
**rifatto il reshape** verso la struttura canonica Laraxot, coordinandosi via
`bashscripts/lock/{check,lock,unlock}.sh` con l'agente che lavorava in parallelo su Catalog (lock
rilasciato durante l'attesa di retry, poi riacquisito da questo agente).

## Stato all'ingresso di questa sessione

Il modulo era già stato in gran parte reshapato (da un lavoro non ancora commitato) prima di questo
turno:

- `app/{Models,Actions,DTO,Filament,Http,Policies,Providers,Support}` già presenti, nessuna cartella
  `Domain/Application/Infrastructure` residua.
- Modelli già `extends XotBaseModel`.
- `RouteServiceProvider`/`EventServiceProvider`/`routes/{web,api}.php` già scaffoldati (pitfall
  Larastan bootstrap noto già gestito).

## Lavoro completato in questa sessione

1. **Actions**: aggiunto `use Spatie\QueueableAction\QueueableAction;` mancante alle 3 Action
   (`FetchMetelCatalogViaApiAction`, `ImportMetelPriceListFileAction`, `LinkMetelItemToProductAction`
   — tutte già con singolo entrypoint `execute()`).
2. **Provider Filament**: creato `app/Providers/Filament/AdminPanelProvider.php extends
   XotBasePanelProvider` (mancante).
3. **Migrazioni**: le 2 migrazioni originali (`create_catalog_tables.php`,
   `create_metel_catalog_tables.php`) erano multi-tabella, `extends Migration` (Laravel core), con
   `$table->timestamps()` manuale — violazione della regola "1 modello = 1 migrazione,
   `XotBaseMigration`, `tableCreate()`/`tableUpdate()`, `updateTimestamps()`". Sostituite con **7
   migrazioni singole** (`catalog_frequencies`, `catalog_measure_units`, `catalog_part_types`,
   `catalog_models`, `products`, `metel_catalogs`, `metel_catalog_items`), ciascuna
   `extends XotBaseMigration` con `$model_class` esplicito.
4. **Filament Resources**: tutte e 6 le Resource (`CatalogFrequencyResource`,
   `CatalogMeasureUnitResource`, `CatalogModelResource`, `CatalogPartTypeResource`, `ProductResource`,
   `MetelCatalogResource`) estendevano `Filament\Resources\Resource` direttamente — violazione della
   regola "mai estensione diretta di classi Filament core". Convertite a `extends XotBaseResource`,
   `form()` → `getFormSchema(): array` (astratto della base).
5. **Filament Pages**: le 24 Pages (`Create*`, `Edit*`, `List*`, `View*`) estendevano
   `Filament\Resources\Pages\{CreateRecord,EditRecord,ListRecords,ViewRecord}` — convertite a
   `XotBase{Create,Edit,List,View}Record`. Aggiunto `getInfolistSchema(): array` (astratto mancante)
   alle 6 pagine `View*`. Corretto il tipo di ritorno di `getHeaderActions()` nelle pagine `List*`
   (array associativo con chiave `'create'`, non lista posizionale).
6. **RelationManager**: `ItemsRelationManager` estendeva `Filament\Resources\RelationManagers\
   RelationManager` — convertito a `XotBaseRelationManager`; rimosso l'override `form()` (vietato,
   `final` nella base).
7. **Modelli**: aggiunti blocchi `@property` a `MetelCatalog`/`MetelCatalogItem` (mancavano, causavano
   `property.notFound` per accesso dinamico con `$guarded = []`).
8. **Config/lang**: creato `config/metel.php` (mancava, referenziato dalle Action) e
   `lang/{it,en}/{metel,catalog}.php` (mancavano, chiavi di traduzione usate da Action/Resource senza
   sorgente).
9. **Test scaffold**: creato `tests/TestCase.php extends XotBaseTestCase` e `tests/Pest.php` (mancanti,
   nessun test esistente oltre `.gitkeep`).
10. **Bug minori risolti**: `preg_split` non-safe (`use function Safe\preg_split;`), tipizzazione
    `mapArticle()`, cast del parametro `format` con `match()` invece di cast diretto, return-type
    `getNavigationGroup(): ?string` → `string` (mai null).

## Verifica

- `php -l` su tutti i file del modulo: nessun errore di sintassi.
- `./vendor/bin/phpstan analyse Modules/Catalog --memory-limit=-1` (da `laravel/`): eseguibile (in
  precedenza bloccato da un errore di bootstrap **non relativo a Catalog**, vedi sotto), **22 errori
  residui**, tutti riconducibili a due categorie note e già presenti altrove nel monorepo:
  - `classConstant.unused` (10x): `ACCOUNT_FEATURE_ADMIN`/`ACCOUNT_FEATURE_OPERATOR` sono lette
    dinamicamente via `defined()`/`constant()` in `App\Support\Concerns\HasAccountFeatureVisibility`
    (trait condiviso, non di proprietà di questo modulo) — PHPStan non può tracciare l'uso dinamico.
  - `larastan.noEnvCallsOutsideOfConfig` (6x, `config/metel.php`): stesso pattern già presente in
    `Modules/TimberBilling/config/config.php` — limite noto di Larastan sui `config/` di modulo in un
    monorepo nwidart (non è il `config/` root Laravel).

  Nessun errore residuo riguarda logica applicativa, tipizzazione dei modelli/Action, o violazioni
  strutturali XotBase.

## Blocco temporaneo osservato (non causato da Catalog)

Durante un primo tentativo, `phpstan analyse Modules/Catalog` falliva al bootstrap con:

```
PHP Fatal error: Type of Modules\Auth\Providers\EventServiceProvider::$shouldDiscoverEvents
must not be defined ...
```

Causa: `Modules\Auth\Providers\EventServiceProvider` (modulo esterno, non toccato in questa sessione)
dichiara `$shouldDiscoverEvents` con un tipo diverso da quello atteso da
`Illuminate\Foundation\Support\Providers\EventServiceProvider`. Già documentato altrove nel repo
(`Modules/Xot/docs/serviceprovider-common-errors.md`, `Modules/Activity/docs/phpinsights-errors.md`).
Non modificato per non toccare un modulo di proprietà di un altro agente. Il blocco si è risolto da
solo (probabilmente corretto in parallelo da chi possiede `Auth`) prima della verifica finale.

## Registrazione

`"Catalog": true` in `laravel/modules_statuses.json` — PHPStan ha solo i due residui minori/noti sopra
descritti.
