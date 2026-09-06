---
title: "Bridge Pest fatti a mano: l'antipattern che gonfia il gate PHPStan"
module: Xot
type: concept
tags: [phpstan, pest, tests, antipattern, gate]
created: 2026-08-24
updated: 2026-08-24
qmd: "phpstan pest bridge uses void function.void method.nonObject group null extension-installer"
related:
  - "./eloquent-magic-properties-rule.md"
  - "../../../../docs/chat/phpstan-modules-regression-campaign.md"
---

# Bridge Pest fatti a mano: l'antipattern

## La regola

**Non si ridichiarano le funzioni di Pest (`uses`, `test`, `it`, `describe`, `beforeEach`,
`afterEach`, `expect`) in nessun namespace del progetto.** Se PHPStan si lamenta degli
helper Pest, la risposta è il plugin ufficiale, non uno stub locale.

## Il caso

`Modules/Xot/tests/Support/PestFunctionBridge.php` era un file generato di 11.914 righe
che, per 192 namespace di test, dichiarava:

```php
namespace Modules\<Modulo>\Tests\Unit {
    function uses(string ...$classAndTraits): void {}
    function test(string $description, ?Closure $closure = null): void {}
    function it(string $description, ?Closure $closure = null): void {}
}
```

In PHP la risoluzione delle funzioni non qualificate cerca **prima** il namespace corrente
e solo dopo quello globale. Ogni file di test namespaced vedeva quindi la versione `void`
al posto di quella vera di Pest. Conseguenze a cascata sul gate:

| Identifier | Conteggio | Perché |
|---|---:|---|
| `function.void` | 514 | `uses(TestCase::class)->group(...)` usa il risultato di una `void` |
| `method.nonObject` | 515 | `Cannot call method group() on null` — il valore di ritorno è `null` |
| `class.notFound` | 265 | il bridge citava cinque moduli inesistenti: AI, Cms, Gdpr, Geo, Limesurvey |

Totale: 1288 finding su 1356. Rimosso il file, il gate è sceso a 68.

## Perché lo stub non serviva

Il commento in testa al file diceva di voler impedire a PHPStan di risolvere gli helper
Pest alle classi `@internal` di Pest. Quel problema ha già una soluzione ufficiale:
`pestphp/pest-plugin-phpstan` fornisce `PestInternalClassAccessIgnoreExtension`, ed è
**caricato in automatico** da `phpstan/extension-installer`.

La verifica è immediata: aggiungere l'extension a mano agli `includes` di `phpstan.neon`
fa fallire l'avvio con

```text
This file is included multiple times:
- vendor/pestphp/pest-plugin-phpstan/extension.neon
```

Se quel messaggio compare, l'extension era già attiva e ogni workaround locale è debito.

## Come riconoscerlo

Centinaia di `function.void` insieme a `Cannot call method group() on null` non sono un
problema dei test: sono la firma di una ridichiarazione. Il controllo:

```bash
grep -rn '^\s*function \(uses\|test\|it\|describe\|beforeEach\|afterEach\|expect\)(' \
  Modules/*/tests
```

Dopo aver rimosso lo stub serve sempre `rm -rf /tmp/phpstan`: la cache calda continua a
riportare i vecchi conteggi.

## Regola derivata

Un file generato che non ha più un generatore nel repository — qui
`bashscripts/tools/generate-pest-phpstan-bridge.php`, che non esiste — non è
infrastruttura: è output orfano. Va trattato come debito, non come contratto.
