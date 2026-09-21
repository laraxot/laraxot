---
title: "HasXotFactory — marker di conflitto git non risolti, PHP parse error"
type: story
module: Xot
story_id: "xot-hasxotfactory-conflict-markers-fix"
slug: xot-hasxotfactory-conflict-markers-fix
status: done
created: 2026-09-11
updated: 2026-09-11
repository: "git@github.com:festionali/base_restaurant_fila5.git"
owner_session: "legacy-ristorante-module-port [243ebe]"
related:
  - laravel/Modules/Xot/docs/stories/18.19.xotbaseresource-final-getformschema-table-illegal-override-repo-wide-fix.story.md
  - laravel/Modules/Xot/docs/stories/18.21.getformschema-ownership-regressione-e-guardia.story.md
  - laravel/Modules/Restaurant/docs/stories/1.1.legacy-port-foundation-and-domains.story.md
---

# HasXotFactory — marker di conflitto git non risolti

## Contesto

Scoperto lavorando al porting `ristorante` legacy -> `Modules/Restaurant` (story
1.1), controllando `database/factories/*` prima di scrivere le mie factory.
`Modules/Xot/app/Models/Traits/HasXotFactory.php` era committato in HEAD con
marker `<<<<<<< HEAD` / `=======` / `>>>>>>> laraxot/dev` non risolti su due
blocchi (firma di `newFactory()`/`factory()`). Trait usata da `XotBaseModel`,
quindi da ogni model di ogni modulo — file non compilabile, parse error al
primo autoload di un `Model::factory()`.

Successivamente si è scoperto che il problema è **repo-wide**: 1175+ file in
`laravel/Modules` hanno marker committati dal commit `57634f67` ("delete
.impeccable/"). La bonifica di massa è tracciata dalla sessione
`base-restaurant-fila5-89` (vedi il suo log in story 1.1 e in
`bashscripts/docs/prompts/51-conflitti-git-current-change.md`, che documenta il
metodo corretto: contare le 4 categorie di blocco, non assumere che un lato
vinca sempre). Questa story copre **solo** `HasXotFactory.php`, il file che ho
risolto io stesso prima che la bonifica di massa partisse.

## Root cause

Il body condiviso del metodo (`app(GetFactoryAction::class)->execute(static::class)`)
ignorava comunque `$count`/`$state` indipendentemente da quale lato del
conflitto veniva scelto — nessuno dei due lati era di per sé corretto. Il
commento rimasto nel blocco di conflitto documentava un bug reale già noto:
`Model::factory(5)` non lanciava errore ma creava silenziosamente 1 record
invece di 5, perché PHP non segnala argomenti posizionali in eccesso su
funzioni che non li dichiarano.

## Fix

Rimossi i marker, ricostruito il trait per rispecchiare esattamente il
contratto di `Illuminate\Database\Eloquent\Factories\HasFactory::factory()`
(letto da vendor): `newFactory()` resta il hook che risolve via
`GetFactoryAction`, `factory($count = null, $state = [])` applica
correttamente `count`/`state` sopra, come fa Laravel stesso.

Verificato `php -l` pulito sul file.

## GitHub (tracciamento)

| Tipo | Repo | # | URL |
|------|------|---|-----|
| Issue | festionali/base_restaurant_fila5 | 3 | https://github.com/festionali/base_restaurant_fila5/issues/3 |
| Discussion (coordinamento) | festionali/base_restaurant_fila5 | 1 | https://github.com/festionali/base_restaurant_fila5/discussions/1 |

## Acceptance criteria

1. ✅ Nessun marker di conflitto residuo in `HasXotFactory.php` (`grep -c '^<<<<<<<\|^=======\|^>>>>>>>'` = 0).
2. ✅ `php -l` pulito.
3. ✅ `factory($count, $state)` applica correttamente conteggio/stato come `Illuminate\Database\Eloquent\Factories\HasFactory::factory()`.
4. ⏳ PHPStan/Pest sul modulo Xot: bloccato dal resto dei 1175 file con marker (story-ombrello: bonifica di massa, vedi `base-restaurant-fila5-89`) — non eseguibile in isolamento su questo solo file finché `XotBaseModel`/`XotBaseResource` restano rotti.

## Log

- 2026-09-11: fix applicato, issue #3 aperta con dettaglio, commit `e599c974`. Scoperta la scala repo-wide del problema (1175+ file) subito dopo, mentre costruivo Filament Resource per Zone/DiningTable — segnalato a entrambe le sessioni peer.
