---
title: "Pest — plugin ufficiali, Rector e PHPStan"
description: Indice plugin Pest 5 per Laraxot — installazione nwidart e link alle guide specialistiche.
document_type: reference
category: testing
status: active
version: 3.0.0
language: it-IT
updated_at: 2026-08-19
related:
  - ./stories/5.17.pest-plugin-stack-complete.story.md
  - ./wiki/concepts/pest-official-plugins.md
  - ./wiki/concepts/pest-phpstan-plugin.md
  - ./rector.md
  - ./stories/5.15.pest-official-plugins.story.md
tags: [pest, plugins, rector, phpstan, nwidart]
---

# Pest — plugin, Rector e PHPStan

Fonti: [plugins](https://pestphp.com/docs/plugins) · [rector](https://pestphp.com/docs/rector) ·
[phpstan](https://pestphp.com/docs/phpstan).

## Guida principale plugin

**→ [pest-official-plugins.md](./wiki/concepts/pest-official-plugins.md)** — inventario completo,
comandi verificati, faker/laravel/livewire, mutate/profanity/type-coverage/arch.

## Dove si dichiarano (nwidart)

`Modules/Xot/composer.json` `require-dev` + merge-plugin. Eccezione root: solo
`allow-plugins.phpstan/extension-installer`.

Dopo edit a `Modules/Xot/composer.json`: **`cd laravel && composer update -W`**.

## Stato plugin (2026-08-19, verificato post `-W`, story 5.17)

| Categoria | Pacchetti | Stato |
|-----------|-----------|-------|
| Core Pest 5 | arch, mutate | ✓ bundled |
| Stack esplicito Xot | rector, phpstan, faker, laravel, livewire, profanity, **type-coverage** | ✓ 7/7 |
| PHPStan infra | extension-installer, larastan (merge) | ✓ |

## Guide specialistiche

| Argomento | Doc |
|-----------|-----|
| PHPStan + `Expectation<T>` | [pest-phpstan-plugin.md](./wiki/concepts/pest-phpstan-plugin.md) |
| Rector sui test | [rector.md](./rector.md) |
| Bootstrap Pest / XotBasePest | [pest4-bootstrap-composer.md](./wiki/concepts/pest4-bootstrap-composer.md) |

## Azione utente phpstan.neon

Con `extension-installer`, rimuovere `includes:` duplicati — vedi
[pest-phpstan-plugin.md](./wiki/concepts/pest-phpstan-plugin.md).
