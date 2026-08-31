---
title: pest phpstan plugin — type inference e regole Pest-aware
description: Installazione pestphp/pest-plugin-phpstan con nwidart, extension-installer, verifica e cleanup phpstan.neon.
document_type: concept
module: Xot
status: active
language: it-IT
updated_at: 2026-08-19
related:
  - ../stories/5.14.pest-phpstan-plugin.story.md
  - ../stories/5.15.pest-official-plugins.story.md
  - ./pest-official-plugins.md
  - ../pest-plugins-rector-phpstan.md
  - ./phpstan-pest-bridge-discipline.md
  - ../rector.md
tags: [pest, phpstan, plugin, nwidart, expect, type-inference]
---

# Pest PHPStan plugin

Fonte: [Pest — PHPStan](https://pestphp.com/docs/phpstan).

## Scopo

Il plugin ufficiale insegna a PHPStan l'API dinamica di Pest:

- `expect($x)` restituisce `Expectation<T>` con narrowing su `->and()`, `->not`, `toBeInstanceOf()`
- higher-order expectations (`expect($m)->name->toBe('…')`)
- regole Pest-aware: test duplicati, `throws()` invalidi, closure vuote, aspettative ridondanti

Senza plugin, sui test compaiono errori `method.internalClass` perché `Pest\Mixins\Expectation` è `@internal`.

## Dove si installa (nwidart)

In **`Modules/Xot/composer.json`** `require-dev` — non in `laravel/composer.json`:

| Pacchetto | Ruolo |
|-----------|--------|
| `pestphp/pest-plugin-phpstan` ^5.0 | Plugin type inference + regole |
| `phpstan/extension-installer` ^1.4 | Auto-registrazione estensioni Composer |

Prerequisito: **Pest 5** (story [5.12](../stories/5.12.pest-5-upgrade.story.md)).

Root `laravel/composer.json`: solo `allow-plugins.phpstan/extension-installer: true` (Composer plugin, non dipendenza Pest).

```bash
cd laravel
composer update
```

Verifica installazione:

```bash
composer show pestphp/pest-plugin-phpstan   # v5.0.2
composer show phpstan/extension-installer   # 1.4.x
```

## Registrazione in PHPStan

### Con extension-installer (scelta attuale)

L'installer registra automaticamente `pest-plugin-phpstan/extension.neon` (vedi
`vendor/phpstan/extension-installer/src/GeneratedConfig.php`).

**Non serve** aggiungere manualmente:

```neon
includes:
    - vendor/pestphp/pest-plugin-phpstan/extension.neon
```

### Senza extension-installer

Aggiungere a `laravel/phpstan.neon` (solo l'utente modifica quel file):

```neon
includes:
    - vendor/pestphp/pest-plugin-phpstan/extension.neon
```

## Cleanup obbligatorio di phpstan.neon (azione utente)

Con `extension-installer` attivo, **rimuovere** da `laravel/phpstan.neon` le righe `includes:` già
gestite dall'installer, altrimenti PHPStan avvisa:

```
These files are included multiple times: larastan, carbon, safe-rule, pest/extension.neon
```

ed esce con **exit 1 senza analizzare**.

Righe da rimuovere (l'installer le ricarica da solo):

```neon
    - ./vendor/larastan/larastan/extension.neon
    - ./vendor/nesbot/carbon/extension.neon
    - ./vendor/thecodingmachine/phpstan-safe-rule/phpstan-safe-rule.neon
    - ./vendor/pestphp/pest/extension.neon
```

**Tenere** (non gestite dall'installer):

```neon
    - ./vendor/phpstan/phpstan/conf/bleedingEdge.neon
```

`pest-plugin-phpstan` viene incluso **solo** via extension-installer — non duplicarlo a mano.

## Comandi

```bash
cd laravel

# Modulo intero (perimetro consigliato per memoria)
php -d memory_limit=-1 ./vendor/bin/phpstan analyse Modules/Rating

# Solo cartella test
php -d memory_limit=-1 ./vendor/bin/phpstan analyse Modules/Progressioni/tests
```

## Verifica funzionale (2026-08-19)

File campione: `Modules/Progressioni/tests/Unit/Models/ProgressioniTest.php`.

| Prima (solo pest base) | Dopo (pest-plugin-phpstan) |
|------------------------|----------------------------|
| `method.internalClass` su `expect()->toBe()` | `Expectation<Progressioni>` tipizzato |
| Nessuna regola Pest | `pest.expectation.redundant`, `pest.test.emptyClosure` |

Esempio output con plugin attivo:

```
Calling toBeInstanceOf() on Expectation<Modules\Progressioni\Models\Progressioni>; assertion is redundant.
🪪 pest.expectation.redundant
```

## Ignorare regole selettivamente

In `laravel/phpstan.neon` (solo utente):

```neon
parameters:
    ignoreErrors:
        - identifier: pest.expectation.redundant
```

Identifier stabili documentati in [Pest PHPStan docs](https://pestphp.com/docs/phpstan).

## Relazione con PestStubs / bridge discipline

- Il plugin **non sostituisce** `PestStubs.php` per helper dominio o `pest(): Configuration` nel bootstrap.
- Resta valida [phpstan-pest-bridge-discipline](./phpstan-pest-bridge-discipline.md): test pubblici in Pest, helper in `TestCase` / `Helpers.php`.
- Con plugin attivo, molti stub manuali su `expect()` diventano superflui; valutare rimozione graduale per modulo.

## Collegamenti

- [Plugin Rector + PHPStan (panorama)](../pest-plugins-rector-phpstan.md)
- [Story 5.14](../stories/5.14.pest-phpstan-plugin.story.md)
- [Rector sui test](../rector.md)
