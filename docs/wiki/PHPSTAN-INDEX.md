---
title: "PHPStan — Router operativo"
type: index
tags: [phpstan, larastan, pest, eloquent, static-analysis, quality]
created: 2026-09-02
updated: 2026-09-02
qmd: "phpstan analyse Modules remediation Pest Mockery Expectation Eloquent generics trait fixture architecture test"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - ./skills/phpstan-pest-remediation.md
  - ./rules/phpstan-pest-tests-stay-pest.md
  - ./rules/phpstan-neon-immutable.md
  - ../../laravel/Modules/Xot/docs/wiki/phpstan-best-practices.md
  - ../../laravel/Modules/Xot/docs/wiki/concepts/phpstan-pest-bridge-discipline.md
---

# PHPStan — Router operativo

> Entry point unico per una campagna `cd laravel && ./vendor/bin/phpstan analyse Modules`.

## Contratto

- Usare esclusivamente `laravel/phpstan.neon`, senza `-c`, `--level`, baseline o
  configurazioni temporanee.
- Correggere la causa nel codice o nel test; non aggiungere `@phpstan-ignore`,
  `assert()` o `@var` inline per forzare il tipo.
- Dopo un fix circoscritto: analisi del modulo, test Pest interessati, poi comando
  completo su `Modules`.

## Routing on-demand

| Segnale | Owner da leggere |
|---|---|
| Pest, Mockery, `method.internalClass` | [phpstan-pest-remediation](skills/phpstan-pest-remediation.md) |
| Bridge Pest o helper di test condivisi | [phpstan-pest-bridge-discipline](../../laravel/Modules/Xot/docs/wiki/concepts/phpstan-pest-bridge-discipline.md) |
| Generics Eloquent e trait | [PHPStan best practices Xot](../../laravel/Modules/Xot/docs/wiki/phpstan-best-practices.md) |
| Configurazione o ignore non abbinati | [phpstan-neon-immutable](rules/phpstan-neon-immutable.md) — l'agente non modifica il neon |
| Fix PHP | [validation-post-edit-rule](rules/validation-post-edit-rule.md) |

## Pattern verificati

- Mockery può restituire `CompositeExpectation` anche per un solo metodo;
  `ExpectationInterface` non dichiara `with()` o `andReturnUsing()`. Un helper
  riusabile deve estrarre e restituire l'`Expectation` concreta dal relativo
  `ExpectationDirector`, con guardie runtime.
- Nei trait Eloquent usare `$this` come modello dichiarante delle relazioni, ad
  esempio `MorphToMany<Rating, $this, MorphPivot, 'pivot'>`, e dichiarare
  `@phpstan-require-extends Model`.
- Le fixture che consumano un trait devono esporre firme precise: un generico
  `array<string, mixed>` può degradare a `mixed` tutto il trait analizzato nel
  contesto della fixture.
- Un test architetturale che segnala un metodo base mancante ha trovato un'API
  realmente incompleta: implementare il contratto, non silenziare il test.

## Definition of done

```bash
cd laravel
./vendor/bin/phpstan analyse Modules/<Owner>
./vendor/bin/pest <test-interessati>
./vendor/bin/phpstan analyse Modules
```

Il risultato finale valido è l'exit code `0` del comando completo.
