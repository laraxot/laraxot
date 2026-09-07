---
title: "PHPStan Modules/Geo — completato"
date: 2026-09-07
scope: [module:Geo, project:base_workorder_fila5]
status: completato
---

# PHPStan Geo — esito

**Esito: fatto (commit + push).**

## Numeri

- Baseline registrata in `docs/chat/phpstan-modules-zero.md` (stale, non
  ri-verificata da nessuno): **105 errori**.
- Scan fresco all'apertura di questo task (`./vendor/bin/phpstan analyse
  Modules/Geo --memory-limit=-1`, cache pulita): **2 errori** — nel frattempo
  uno swarm parallelo (Epic 4 "filament-canonical-structure") aveva già in
  corso l'estrazione Schemas/Tables su `AddressResource`/`LocationResource`
  del modulo, riducendo silenziosamente il resto. Quel lavoro non è mio e non
  è stato toccato.
- Dopo il fix: **0 errori** (confermato due volte, prima e dopo il push, cache
  pulita entrambe le volte).

## Causa radice

`app/Filament/Forms/Components/AddressField.php:41` e
`AddressesField.php:100` chiamavano
`Modules\Geo\Filament\Resources\AddressResource\Schemas\AddressForm::getFormSchema()`
in forma statica. `XotBaseResourceForm::getFormSchema()` è dichiarato
`abstract public function` (metodo di istanza, mai `static`) — contratto
corretto, i due consumer erano rimasti indietro rispetto a una migrazione
precedente.

## Fix

```php
// prima
$baseSchema = AddressForm::getFormSchema();
// dopo
$baseSchema = app(AddressForm::class)->getFormSchema();
```

Stesso pattern già in uso nel modulo Activity (verificato nei test
`Modules/Activity/tests/Unit/Filament/*`). Nessun `@phpstan-ignore`, nessuna
modifica a `phpstan.neon`, nessun `mixed` introdotto.

Corretto anche `Modules/Geo/phpunit.xml`: `bootstrap="vendor/autoload.php"`
era relativo alla cwd (rotto), corretto in `../../vendor/autoload.php`
(relativo al file, come Notify/Xot/UI/Lang/Tenant/Gdpr) — necessario per
eseguire il quality gate Pest richiesto da questa stessa story.

## File toccati (commit `b06c8c26`, modulo `Modules/Geo`)

- `app/Filament/Forms/Components/AddressField.php`
- `app/Filament/Forms/Components/AddressesField.php`
- `phpunit.xml`
- `docs/coverage.md`
- `docs/stories/01.Geo-phpstan-fix.story.md`

Non toccati (WIP di un'altra sessione, ~50 file, non validati da me): le
Resource `AddressResource`/`LocationResource` e relative pagine/Schemas in
corso di estrazione al pattern canonico, e i numerosi `docs/*.md` in
modifica per un cleanup di documentazione concorrente.

## Quality gate

- PHPStan: 0 errori (`Modules/Geo`, cache pulita).
- PHPMD: nessun nuovo finding sui 2 file toccati (verificato riga per riga
  contro `git diff`); debito pre-esistente non toccato (complessità,
  `BooleanArgumentFlag`, `UnusedLocalVariable` altrove nello stesso file, non
  sulle righe modificate).
- PHPInsights: non eseguito — rimosso da questo repo, incompatibile con
  Pest 5 (confermato dall'hook di verifica post-edit della sessione). Non è
  un blocco imputabile a questo task.
- Pest: suite completa del modulo (`Modules/Geo/tests`, 938s):
  **399 passed, 104 failed, 16 risky (1026 assertions)**. I 104 fallimenti
  sono pre-esistenti (chiamate rete reali a Google Maps/Bing Maps/Mapbox/
  IPGeolocation senza mock disponibile in questo ambiente, rendering blade di
  `GeoMapWidgetTest`, messaggi di eccezione hard-coded disallineati) — stessa
  famiglia già annotata nella sintesi narrativa precedente di
  `Modules/Geo/docs/coverage.md` (2026-09-06). Nessuna regressione
  riconducibile a questo fix: diff di una riga per file, test mirato
  `FilamentComponentsTest::"AddressField can be instantiated"` passa.
- Coverage: registrata prima/dopo in `Modules/Geo/docs/coverage.md` (prima:
  solo narrativa, Pest non partiva per bootstrap rotto; dopo: 519 test
  raccolti, numero quantitativo per la prima volta).

## BMAD

Story aggiornata: `Modules/Geo/docs/stories/01.Geo-phpstan-fix.story.md`
(esisteva già uno stub vuoto con questo nome/ID, riempito invece di
duplicarlo; altre 3 story-stub Geo/phpstan pre-esistenti — `phpstan-Geo-fix.md`,
`phpstan-batch.story.md`, `phpstan-fix-1788762467.story.md` — lasciate intatte
e referenziate, non cancellate).

## Memoria

Nuova nota: `bashscripts/ai/wiki/memories/geo-addressform-schema-reuse-static-call-bug.md`
— distingue il caso "riuso legittimo di `Schemas/{Model}Form::getFormSchema()`
da un componente esterno non-Resource" (fix: `app(Class::class)->metodo()`) dal
pattern già noto "override-ponte morto sulla Resource stessa" (fix: cancellare
l'override). Un'altra sessione concorrente (modulo Lang) ha già arricchito
questa nota con una terza variante osservata nei test — lasciata intatta.

## Git

Commit `b06c8c26` su `laraxot/dev` (remote `laraxot`, repo
`module_geo_fila5`), nessun conflitto al merge (nessun commit nuovo lato
remoto al momento del fetch), push riuscito.
