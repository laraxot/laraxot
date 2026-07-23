---
title: "Build PHPInsights .phar"
type: concept
tags: [phpinsights, phar, build]
created: 2026-07-14
updated: 2026-07-14
qmd: "phpinsights-phar-build build phpinsights .phar"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index-2.md"
  - "./00-index.md"
  - "./absolute-completion-100.md"
  - "./acronym-naming-conventions-1.md"
  - "./acronym-naming-conventions-2.md"
  - "./acronym-naming-conventions.md"
  - "./action-plan-immediate.md"
---

# Build PHPInsights .phar

Procedura per costruire il file `phpinsights.phar` usato come strumento qualità codice. PHPInsights non fornisce release phar ufficiali; si usa [humbug/box](https://github.com/humbug/box) per crearlo localmente.

## Scopo

- **Indipendenza**: nessuna dipendenza in `composer.json` del progetto
- **Portabilità**: un solo file riutilizzabile tra progetti
- **CI/CD**: facile da cachare e scaricare

## Prerequisiti

- PHP 8.2+
- Composer
- Estensione phar abilitata

## Build

```bash
cd laravel/tools/build-phpinsights
./build.sh
```

Lo script esegue `composer install` e `box compile`. L'output è `laravel/tools/phpinsights.phar`.

## Struttura

```
laravel/tools/
  build-phpinsights/     # ambiente di build
    composer.json
    box.json
    build.sh
  phpinsights.phar      # output
  phpmd.phar
```

## Configurazione box.json

- **main**: entry point bin di PHPInsights
- **output**: `../phpinsights.phar` (parent di build-phpinsights)
- **dump-autoload**: `false` per evitare errori con php-scoper
- **check-requirements**: `false` per conflitti symfony/cache vs redis
- **directories**: `["vendor"]` per includere autoload nel phar

## Verifica

```bash
cd laravel
php tools/phpinsights.phar --version
php tools/phpinsights.phar analyse Modules/Xot --no-interaction
```

Exit code 4 indica che PHPInsights ha trovato problemi nel codice analizzato (comportamento atteso).

## Collegamenti

- [quality-tools-setup](quality-tools-setup.md) — uso di PHPMD e PHPInsights
- [build-phpinsights README](../laravel/tools/build-phpinsights/README.md) — dettagli build
