---
title: Rating — test e coverage
description: Come si eseguono i test del modulo Rating, su quale perimetro si misura la coverage e perché alcuni test sono in skip condizionato.
document_type: doc
category: testing
status: active
version: 1.2.0
language: it-IT
updated_at: 2026-08-24
related:
  - ../../../../bashscripts/docs/prompts/03-quality-gates.md
  - ../../../../bashscripts/ai/wiki/concepts/quality-gate-canonical-commands.md
  - ../../../../docs/bmad/stories/3.1.rating-coverage-and-xotbasepest.story.md
  - ../../Xot/tests/XotBasePest.php
tags: [pest, coverage, xdebug, phpunit, skip, xotbasepest]
---

# Rating — test e coverage

## Eseguire la suite

```bash
# cwd: laravel/
./vendor/bin/pest -c Modules/Rating/phpunit.xml --no-coverage
```

`Modules/Rating/phpunit.xml` esiste per una ragione sola: **spostare il perimetro di
coverage** su `Modules/Rating/app`. La `laravel/phpunit.xml` di root dichiara
`<source><include><directory>app</directory>`, cioè lo scheletro applicativo, non i moduli:
misurata da lì, la coverage di Rating non compare affatto.

Il `bootstrap` è `../../vendor/autoload.php` — **relativo al file di config**, non alla cwd.
Le `phpunit.xml` di `Modules/Activity` e `Modules/Job` dichiarano `vendor/autoload.php` e
per questo non sono eseguibili da `laravel/`.

## Coverage

```bash
# cwd: laravel/
XDEBUG_MODE=coverage ./vendor/bin/pest -c Modules/Rating/phpunit.xml --coverage --min=0
```

| Data | Totale | Suite |
|------|--------|-------|
| 2026-08-19, baseline | **3,0 %** | 3 passati, 8 rossi |
| 2026-08-19, primo giro | **13,1 %** | 34 passati, 7 skipped |
| 2026-08-19, secondo giro | **32,3 %** | 69 passati, 7 skipped |
| 2026-08-19 22:10, story 5.24 | **71,1 %** | 84 passati, 9 skipped — gate `--min=50` ✅ |
| 2026-08-20, story 5.25 | **100 %** | 92 passati, 0 skipped — gate `--min=80` ✅ |
| 2026-08-20, story 5.26 | **100,0 %** | 102 passati, 7 skipped — gate `--min=100` ✅ |

Gate floor 100% (story 5.26):

```bash
XDEBUG_MODE=coverage ./vendor/bin/pest -c Modules/Rating/phpunit.xml --coverage --min=100
```

Pest stampa la tabella di coverage solo quando il run esce `0`.

## XotBasePest (gap aperto — story 3.1)

Il bootstrap [`Modules/Rating/tests/Pest.php`](../tests/Pest.php) deve allinearsi ad ADR-003:

```php
require_once __DIR__.'/../../Xot/tests/XotBasePest.php';
```

- **Vietato** `uses(TestCase::class)->in(...)` nel bootstrap (PHPStan `method.internalClass`).
- Ogni file test dichiara `uses(\Modules\Rating\Tests\TestCase::class);` da solo.
- Helper condivisi: prefisso `xot*` da [`XotBasePest.php`](../../Xot/tests/XotBasePest.php).

Story: [`docs/bmad/stories/3.1.rating-coverage-and-xotbasepest.story.md`](../../../../docs/bmad/stories/3.1.rating-coverage-and-xotbasepest.story.md).

Al **100 % statement** (5.26): tutto `Modules/Rating/app`, inclusi `HasRatingsTrait`,
`HasLikes`, `BaseRating::scopeWithExtraAttributes`, policy `isOwner`, action sum con
`rating_id`, widget StatsOverview con/senza record.

### HasLikes e fixture `Like`

`HasLikes` referenzia `Modules\Rating\Models\Like`; in questa installazione il contratto è
esercitato dalla fixture `tests/Unit/Fixtures/Like.php`, caricata esplicitamente dal test e
dichiarata con lo stesso FQCN. Il namespace è parte del contratto: spostarlo sotto
`Modules\Rating\Tests` rende la relazione nativa irrisolvibile sia a runtime sia per
PHPStan. **Nessun exclude** in `phpunit.xml`: il codice è raggiungibile e testato.

Host stub per `HasRatingsTrait`: `tests/Unit/Fixtures/RatingsHostStub.php` nel namespace
`Modules\Rating\Models\` (necessario a `XotBaseModel::getClassName()` che cerca `Models\`
nello stack). Estende `AbstractRatingsHost`, non `Model` nudo: così gli accessor e le
relazioni non arrivano a PHPStan come `mixed` (famiglia E, story 4.26). **Nessun file
test cancellato** — solo guardie e generics.

Story campagna: [`docs/bmad/stories/4.26.coda-moduli-phpstan-zero.story.md`](../../../../docs/bmad/stories/4.26.coda-moduli-phpstan-zero.story.md).

`--coverage-filter` **non** sposta il perimetro: Pest lo accetta e lo ignora.

## Skip condizionato, non skip permanente

`RatingTest` tocca le tabelle `ratings` e `rating_morphs`. Il `TestCase` del modulo rimappa
tutte le connessioni sqlite su `database/database.sqlite`, che non contiene lo schema del
modulo (i test non lanciano migration: mai `RefreshDatabase`, mai `migrate:fresh`). Quei due
test sono quindi in skip **valutato a runtime**:

```php
})->skip(fn (): bool => TestCase::ratingDbUnavailable(), 'DB `rating` non raggiungibile: blocco di ambiente.');
```

`TestCase::ratingDbUnavailable()` interroga davvero l'ambiente (`getPdo()` + `hasTable()`):
quando lo schema c'è, i test ripartono da soli. È l'equivalente per-test dell'exit `3` del
quality gate. Uno skip **incondizionato** sarebbe invece un test spento per sempre: non si usa.

`RatingApiTest` è in skip di blocco (`describe(...)->skip(...)`): le rotte `/api/ratings` non
sono registrate in questa install, che è a architettura Folio/Actions. Il file precedente
chiamava `skip('…')` dentro `beforeEach`, ma **`skip()` non è una funzione globale di Pest**:
i cinque test risultavano *failed* con `Call to undefined function`, non skipped.

## Helper dei test

Gli helper stanno come metodi statici su `Modules\Rating\Tests\TestCase`, non come funzioni
in `tests/Pest.php`: passando `-c Modules/Rating/phpunit.xml`, Pest carica
`laravel/tests/Pest.php` (root della cwd) e **non** quello del modulo. Per lo stesso motivo
ogni file di test ripete `uses(TestCase::class);` in testa.

## Traduzioni

`SupportedLocale::getLabel()` passa da `TransTrait::transClass()`, che ritorna `'fix:'.$chiave`
quando la traduzione manca. Le label vivono in `Modules/Rating/lang/it/supported_locale.php`.
Un test che asserisce la chiave grezza sta certificando il buco: si asserisce la label.

Il modulo ha solo `lang/it`. Un `App::setLocale('en')` dentro un test fa scrivere all'adapter
Lang i file mancanti e fallisce con `Failed to open stream` perché `lang/en/` non esiste: i
test leggono il locale corrente con `SupportedLocale::fromString(App::getLocale())`.

## Lacune trovate, non colmate

Due classi hanno un contratto **vuoto**. I test le fotografano com'è, con un commento che
dice di aggiornarli quando verranno riempite: un test che asserisce quello che vorremmo
sarebbe rosso a vuoto, uno che asserisce quello che c'è si accorge del cambiamento.

| Classe | Stato |
|--------|-------|
| `Filament/Resources/RatingMorphResource/Schemas/RatingMorphForm` | `getFormSchema()` ritorna `[]`, quindi il create/edit di `RatingMorphResource` non renderizza campi — mentre `RatingMorphInfolist`, della stessa resource, ne dichiara otto |
| `View/Components/Dashboard/Item` | `render()` ritorna la stringa vuota: componente registrato, nessuna view |

## Cosa non è testabile a unit

I componenti Filament non si ispezionano fuori da uno `Schema`: `Block::getChildComponents()`
solleva `Typed property … Component::$container must not be accessed before initialization`.
Restano coperti a costo zero i metodi statici che ritornano array (`getTableColumns()`,
`getTableActions()`, `getTableBulkActions()` — vedi `ListRatingsPageTest`) e le factory
(`::create()`, `->getName()`, `->getLabel()`).
