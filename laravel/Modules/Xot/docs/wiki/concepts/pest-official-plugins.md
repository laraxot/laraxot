---
title: pest plugin ufficiali — installazione e uso con nwidart
description: Panorama completo dei plugin Pest 5 ufficiali, dove dichiararli, comandi verificati e migrazione da PestStubs manuali.
document_type: concept
module: Xot
status: active
language: it-IT
updated_at: 2026-08-19
related:
  - ../pest-plugins-rector-phpstan.md
  - ./pest-phpstan-plugin.md
  - ../rector.md
  - ../stories/5.17.pest-plugin-stack-complete.story.md
  - ../stories/5.16.pest-plugin-stack-nwidart.story.md
  - ../../../../../../docs/bmad/stories/4.7.pest-phpstan-plugin-stack-audit.story.md
tags: [pest, plugins, faker, laravel, livewire, profanity, rector, phpstan, nwidart, quality-gate]
---

# Plugin ufficiali Pest

Fonte: [Pest — Plugins](https://pestphp.com/docs/plugins).

## Dove si installano (nwidart)

Tutte le dipendenze Pest stanno in **`Modules/Xot/composer.json`** `require-dev`. Con
`wikimedia/composer-merge-plugin` confluiscono in `laravel/vendor/`. **Non** usare
`composer require` dalla root (scriverebbe in `laravel/composer.json`).

Dopo **ogni** modifica a `Modules/*/composer.json`:

```bash
cd laravel
composer update -W
```

`-W` (`--with-all-dependencies`) risolve la catena completa — obbligatorio con merge-plugin nwidart.

## Inventario installato (2026-08-19, post `composer update -W`)

### Plugin richiesti (require-dev espliciti in Xot)

| Pacchetto | Versione | Repo | Funzione |
|-----------|----------|------|----------|
| [pest-plugin-rector](https://github.com/pestphp/pest-plugin-rector) | 5.0.3 | GitHub | Set Rector sui test — [rector.md](../rector.md) |
| [pest-plugin-phpstan](https://github.com/pestphp/pest-plugin-phpstan) | 5.0.2 | GitHub | Type inference `expect()` — [pest-phpstan-plugin.md](./pest-phpstan-plugin.md) |
| [pest-plugin-faker](https://github.com/pestphp/pest-plugin-faker) | 5.0.0 | GitHub | `Pest\Faker\fake()` |
| [pest-plugin-laravel](https://github.com/pestphp/pest-plugin-laravel) | 5.0.1 | GitHub | HTTP, DB, Artisan |
| [pest-plugin-livewire](https://github.com/pestphp/pest-plugin-livewire) | 5.0.0 | GitHub | `Pest\Livewire\livewire()` |
| [pest-plugin-profanity](https://github.com/pestphp/pest-plugin-profanity) | 5.0.0 | GitHub | `./vendor/bin/pest --profanity` |
| [pest-plugin-type-coverage](https://github.com/pestphp/pest-plugin-type-coverage) | 5.0.2 | GitHub | `./vendor/bin/pest --type-coverage` |
| `rector/rector` | 2.6.3 | — | motore Rector (peer di plugin-rector) |
| [phpstan/extension-installer](https://github.com/phpstan/extension-installer) | 1.4.3 | GitHub | auto-registra estensioni PHPStan (incl. pest) |

### Inclusi con Pest 5 (dipendenze di `pestphp/pest`, non duplicare)

| Pacchetto | Versione | Funzione |
|-----------|----------|----------|
| `pestphp/pest-plugin` | 5.0.0 | Bootstrap plugin system |
| `pestphp/pest-plugin-arch` | 5.0.0 | Architettura (`arch()`) |
| `pestphp/pest-plugin-mutate` | 5.0.2 | `--mutate` |

> **Nota:** `type-coverage` e `profanity` sono anche transitivi di `pestphp/pest`; la dichiarazione
> esplicita in Xot documenta l'intent e garantisce il merge lock (story 5.17).

### `laravel/composer.json` — resta minimo

Nessuna riga `pestphp/*` in require/require-dev root. Solo `allow-plugins` necessari.

### Non installati (opzionali / fuori scope doc plugins)

- `pestphp/pest-plugin-browser` — browser testing Pest 4+ (Playwright); valutare a parte

---

## Plugin Faker

```php
use function Pest\Faker\fake;

it('genera dati localizzati', function (): void {
    $nome = fake('it_IT')->firstName();
    expect($nome)->toBeString();
});
```

Verificato: `Faker\Generator` con locale `it_IT`.

---

## Plugin Laravel

Registra funzioni namespaced `Pest\Laravel\*` (`actingAs`, `get`, `post`, `getJson`, …) e assertion DB
(`assertDatabaseHas`, `seed`, …).

### Comandi Artisan

```bash
php artisan pest:test UsersTest           # feature test
php artisan pest:test UsersTest --unit     # unit test
php artisan pest:dataset Emails            # dataset condiviso
```

Verificato: entrambi i comandi registrati dopo installazione.

### Esempio HTTP

```php
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

it('risponde autenticato', function (): void {
    actingAs($user);
    getJson('/api/resource')->assertOk();
});
```

---

## Plugin Livewire

```php
use App\Livewire\Counter;
use function Pest\Livewire\livewire;

it('incrementa', function (): void {
    livewire(Counter::class)
        ->call('increment')
        ->assertSee('1');
});
```

Richiede `livewire/livewire` (già in Xot `require`).

---

## Type Coverage

Misura la copertura dei **tipi** nel codice applicativo (non confondere con code coverage PHPUnit).

```bash
./vendor/bin/pest --type-coverage Modules/Rating/tests
./vendor/bin/pest --type-coverage --min=90
```

Verificato su Rating: report per file con percentuale (es. `Total: 95.3%`).

---

## Profanity

Scansiona il codice sorgente (non solo test) alla ricerca di termini offensivi.

```bash
./vendor/bin/pest --profanity
./vendor/bin/pest --profanity Modules/Rating/tests
```

Verificato: `PASS — No profanity found`.

---

## Mutation testing

Valuta la **qualità** dei test mutando il codice sorgente.

```bash
./vendor/bin/pest --mutate --covered-only --min=80
./vendor/bin/pest --mutate --class=Modules\\Rating\\Models\\Rating
```

Flag utili: `--parallel`, `--bail`, `--stop-on-untested`. Costoso in CPU — usare per modulo lockato.

---

## Arch preset

Fornisce API `arch()` per test di architettura (namespace, dependency rules). Esempio:

```php
arch('controllers')
    ->expect('Modules\Rating\Http\Controllers')
    ->toExtend('Modules\Xot\Http\Controllers\XotBaseController');
```

Vedi [Pest Arch](https://pestphp.com/docs/arch-testing).

---

## Migrazione da `PestStubs.php` manuali

Prima dell'installazione, stub duplicati compensavano l'assenza dei plugin:

| File | Stato | Azione |
|------|-------|--------|
| `Modules/Xot/tests/PestStubs.php` | namespace `Pest\Laravel\*` per PHPStan | **Tenere** finché PHPStan non usa solo `pest-plugin-phpstan`; non `require` a runtime |
| `Modules/Activity/tests/PestStubs.php` | — | **Rimosso** (story 3.10) — plugin ufficiali + `pest()->extend` |

Con `pest-plugin-laravel` + `pest-plugin-livewire` installati, i test devono usare le funzioni ufficiali
(`use function Pest\Laravel\…`, `use function Pest\Livewire\livewire`), non stub che lanciano
`RuntimeException`.

---

## Verifiche eseguite

| Check | Esito |
|-------|-------|
| `composer update` faker/laravel/livewire | OK, 0 rimozioni dev |
| `php artisan pest:test` / `pest:dataset` | registrati |
| `pest Modules/Rating/tests/Unit/SupportedLocaleTest.php` | 11 passed |
| `pest --type-coverage` | report 95.3% |
| `pest --profanity` | PASS |
| `Pest\Faker\fake('it_IT')` | OK |

---

## Collegamenti

- [Panorama Rector + PHPStan](../pest-plugins-rector-phpstan.md)
- [PHPStan plugin](./pest-phpstan-plugin.md)
- [Story 5.15](../stories/5.15.pest-official-plugins.story.md)
