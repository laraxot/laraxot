---
tags: [documentation]
created: 2026-09-26
qmd: "README"
issues: []
discussions: []
title: "Rating Module"
type: documentation
module: Rating
updated: 2026-10-14
status: active
---

# Rating Module

Sistema di criteri di valutazione polimorfici (`ratings` / `rating_morph`), agganciabile a qualsiasi model con
`HasRating` o `HasRatingsTrait`. In questo repository serve la valutazione del cittadino sui ticket risolti (Fixcity).
Le versioni precedenti di questo README citavano le schede HR (Performance, IndennitaResponsabilita): quel modulo non
esiste qui, i riferimenti sotto `bmad/` e `stories/` sono storia di un altro progetto.

Ogni affermazione dell'ingresso e' stata confrontata con il codice il 2026-10-14.

## Identita' e ownership

- Namespace `Modules\Rating`, alias `rating`, repository git separato.
- Provider in `module.json`: `RatingServiceProvider` e `Providers\Filament\AdminPanelProvider`; il primo e' anche in `laravel/config/app.php`. Modulo `true` in `laravel/modules_statuses.json` e nello stato del tenant Fixcity.
- Indice operativo: [00-INDEX.md](./00-index.md). Architettura del modulo: [ARCHITECTURE.md](../ARCHITECTURE.md) e [architecture.md](./architecture.md).

## Cosa contiene davvero

| Area | Contenuto verificato | Percorso |
|---|---|---|
| Modelli | `Rating`, `RatingMorph` (tabella `rating_morph`), `Like`, basi `BaseRating`, `BaseRatingMorph`, `AbstractRatingsHost` | `app/Models/` |
| Contratti | `RatingContract`, `HasRatingContract`, `HasLikeContract`, `RatingsFormCallerContract` | `app/Models/Contracts/`, `app/Contracts/` |
| Trait | `HasRating`, `HasRatingsTrait` (form dei criteri, sync, media), `RatingTrait`, `HasLikes` | `app/Models/Traits/` |
| Actions | 3: `GetCountByModelRatingIdAction`, `GetRatingOptsByModelAction`, `GetSumByModelRatingIdAction` | `app/Actions/HasRating/` |
| Dati e enum | `RatingData`, `RatingMorphData`, `RatingBlockData`; `RuleEnum`, `SupportedLocale` | `app/Datas/`, `app/Enums/` |
| Filament | `RatingResource`, `RatingMorphResource` (e basi), `RatingsRelationManager`, `RatingsSection`, `RatingsColumn`, `HasRatingValuesFilter`, blocco `Rating`, widget `StatsOverview`, azione `BetTableAction` | `app/Filament/` |
| Policy | `RatingPolicy`, `RatingMorphPolicy` e due basi | `app/Models/Policies/` |
| Migrazioni | tre `create_ratings_table` e tre `create_rating_morph_table` convivono in `database/migrations/` (vedi `stories/rating-morph-migrazioni-doppie.story.md`) | `database/migrations/` |
| Test | 29 file, 182 chiamate `it()`/`test()` | `tests/` |

## Chi lo usa davvero in questo repository

- Fixcity: `Modules\Fixcity\Actions\SubmitCitizenTicketRatingAction` (valutazione 1-5 su ticket risolto, salvata come `RatingMorph`), `GetCitizenRatingAggregateAction`, le tre Actions in `Actions/TicketCitizenRating/`, il concern `InteractsWithTicketCitizenRating` (usa `HasRating`) e la chiave `rating` in `Modules/Fixcity/config/morph_map.php`.
- Blog: 7 file di `Modules/Blog/app` importano classi Rating (`Article` importa `Rating`, `RatingMorph` e `HasRating`); il modulo Blog non e' usato da Fixcity.
- Xot: `XotBaseMorphPivot` cita `Modules\Rating\Models\RatingMorph` solo in un commento di esempio.

## Limiti

1. Nessuna rotta applicativa e nessun controller: `routes/web.php` e `routes/api.php` sono vuoti, `app/Http/*` contiene solo `.gitkeep`. Le uniche rotte (11 in `php artisan route:list --json`, 2026-10-14) sono del pannello Filament `rating/admin`. Non esiste un'interfaccia pubblica di questo modulo: la valutazione del cittadino e' esposta da Fixcity.
2. Le view `resources/views/livewire/favorite*.blade.php`, `rate/*.blade.php` e `rate_it.blade.php` non hanno classi Livewire in `app/` e nessun file del progetto le richiama con il namespace `rating::` (grep del 2026-10-14): sono residui, non funzioni.
3. `config/config.php` ha tre chiavi (`name`, `icon`, `navigation_sort`): non ci sono opzioni di comportamento.
4. Suite Pest non affidabile oggi: su DB usa-e-getta il 2026-10-14 tutti i 182 test falliscono con `SQLiteDatabaseDoesNotExistException` (0 asserzioni); il 2026-09-29 la memoria `bashscripts/ai/wiki/memories/rating-pest-suite-pre-existing-breakage.md` registrava 145 passati, 23 falliti, 5 saltati.

## Quick start

Da `laravel/`:

```bash
# rotte del modulo (verificato il 2026-10-14: 11, tutte del pannello Filament rating/admin)
php artisan route:list --name=rating
# test su database usa-e-getta (la variabile vuota evita `unbound variable` dello script)
DB_DATABASE_LIMESURVEY= bash ../bashscripts/tools/pest-scratch-db.sh Modules/Rating/tests
```

## Navigazione docs

| Area | Path |
|------|------|
| **BMAD attivo** (Select «altro» + note) | [bmad/README.md](bmad/README.md) |
| Architecture / index generici | [architecture.md](architecture.md) · [index.md](index.md) |
| Design Select+Textarea | [stories/rating-altro-option-conditional-textarea-design.story.md](stories/rating-altro-option-conditional-textarea-design.story.md) |
| Criteri a scelta | [criteri-a-scelta-multipla.md](criteri-a-scelta-multipla.md) |

## Canon UI (2026-09-16): una riga

Con figli: **Select + Textarea** sempre; option «altro» = `''`; placeholder = `null`;
`note` required solo se `selectIsOther === ''`. Dettaglio: [bmad/](bmad/README.md).

## GitHub (pack attuale)

- Design: https://github.com/laraxot/module_rating_fila5/issues/57
- Impl: https://github.com/laraxot/module_rating_fila5/issues/58
- Discussion: https://github.com/laraxot/module_rating_fila5/discussions/59
- D-8 filtro: https://github.com/laraxot/module_rating_fila5/issues/60

## Nota conflitti docs

Questo README aveva marker di merge non risolti (`<<<<<<< HEAD`); ripulito 2026-09-16.
Preferire sempre `docs/bmad/` per lavoro in corso; bozze in `docs/stories/` con
`ALTRO_KEY='altro'` sono superseded-pointer.

## Documentation

- [On-Demand Pattern](./on-demand-pattern.md): Pattern per caricamento efficiente
- [QMD Setup](./qmd-setup.md): Configurazione ricerca locale
- [Performance](./performance-optimization.md): Metriche e best practice
- [Project Structure](./project-structure.md): Directory layout
- [Conflict Resolution](./conflict-resolution.md)
