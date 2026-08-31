---
title: pest rector — refactoring meccanico dei test
description: Installazione, configurazione nwidart e uso di pestphp/pest-plugin-rector + rector/rector sui test dei moduli Laraxot.
document_type: guide
module: Xot
status: active
language: it-IT
updated_at: 2026-08-19
related:
  - ./stories/5.13.pest-rector.story.md
  - ./stories/5.12.pest-5-upgrade.story.md
  - ./wiki/concepts/pest4-bootstrap-composer.md
  - ./wiki/concepts/phpstan-pest-bridge-discipline.md
  - ../../../rector.php
tags: [pest, rector, nwidart, quality-gate, test]
---

# Pest Rector — test dei moduli nwidart

## Scopo

Automatizzare conversioni meccaniche sulla suite Pest (assert PHPUnit → `expect()`, catene
ridondanti, upgrade di major) con uno strumento deterministico invece di refactor manuali su
centinaia di file.

Fonte ufficiale: [Pest — Rector](https://pestphp.com/docs/rector).

## Dove vivono le dipendenze (nwidart)

Con `wikimedia/composer-merge-plugin` le dipendenze dev dei moduli confluiscono nel vendor
della root Laravel. **Non** aggiungere Pest/Rector in `laravel/composer.json`:

| Pacchetto | Dichiarato in |
|-----------|---------------|
| `pestphp/pest` ^5.1 | `Modules/Xot/composer.json` require-dev |
| `pestphp/pest-plugin-rector` ^5.0 | idem |
| `rector/rector` ^2.6 | idem |

Installazione (dalla root `laravel/`):

```bash
composer update
```

Verifica:

```bash
./vendor/bin/pest --version    # Pest 5.x
./vendor/bin/rector --version  # Rector 2.6.x
```

## Configurazione

File unico: [`laravel/rector.php`](../../../rector.php).

| Scelta | Perché |
|--------|--------|
| Perimetro `Modules/*/tests` + `tests/` | I moduli nwidart hanno test isolati; **non** `__DIR__` intero (vendor, storage, app) |
| `PestSetList::CODING_STYLE` | Set ufficiale Pest per lo stile dei test |
| `ChainExpectCallsRector` con `merge_different_variables => false` | Il default unisce aspettative su **variabili diverse** con `->and()` e cambia la semantica dell'asserzione |
| `withSkip` su vendor/storage/cache/docs | Evita rumore e file non-test |

## Comandi

Sempre **dry-run prima** di scrivere:

```bash
cd laravel

# Anteprima (non modifica file)
vendor/bin/rector process --dry-run

# Solo un modulo (consigliato)
vendor/bin/rector process Modules/Rating/tests --dry-run

# Applicazione reale — solo modulo lockato, gate dopo
vendor/bin/rector process Modules/Rating/tests
```

## Risultato dry-run (2026-08-19)

Su tutti i test dei moduli: **633 file candidati**. Regole più frequenti:

- `UsesToExtendRector` — propone `pest()->extend(TestCase::class)` al posto di `uses(TestCase::class)`
- `ConvertAssertToExpectRector` — assert PHPUnit → `expect()`
- `ChainExpectCallsRector` — catene `expect()` sullo stesso soggetto

### Attenzione: `UsesToExtendRector` vs PHPStan

Nel bootstrap condiviso preferiamo `uses(TestCase::class)` o
`pest()->extend(...)->in('.')` **solo** dove `PestStubs.php` copre `pest(): Configuration`
(vedi [phpstan-pest-bridge-discipline](./wiki/concepts/phpstan-pest-bridge-discipline.md)).
Prima di applicare in massa `UsesToExtendRector`, verificare PHPStan sul modulo.

### Attenzione: semantica, non solo sintassi

`ConvertAssertToExpectRector` e `UseToThrowRector` possono cambiare **cosa** viene verificato.
Applicare **per modulo lockato**, con Pest + PHPStan + coverage dopo ogni batch.

## Prerequisito: Pest 5

`pestphp/pest-plugin-rector` richiede Pest ^5 e Rector ^2.6. L'upgrade da Pest 4 è documentato in
[story 5.12](./stories/5.12.pest-5-upgrade.story.md) (rimozione doppione PHPInsights dal merge).

PHPInsights resta isolato in `laravel/tools/phpinsights/` — non va in `require-dev` di Xot.

## Collegamenti

- [Story 5.13 — Pest Rector](./stories/5.13.pest-rector.story.md)
- [Pest 4 bootstrap Composer](./wiki/concepts/pest4-bootstrap-composer.md)
- [ADR-014 — bootstrap Pest](../../../bmad-output/architecture.md) (se presente)
