---
title: "CanComment contract — compatibilità PHP 8.4 con Eloquent"
type: concept
module: Comment
tags: [comment, can-comment, contract, php84, eloquent]
created: 2026-06-06
updated: 2026-06-10
qmd: "CanComment contract getKey mixed PHP 8.4 Eloquent Model compatible fatal error"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/291"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/273"
related:
  - ../decisions/adr-models-contracts-vs-app-contracts.md
  - ../../../../../../docs/wiki/memories/models-contracts-vs-app-contracts.md
  - ../../../../../../docs/wiki/memories/module-providers-manifest-not-manual-register.md
---

# `CanComment` — firme compatibili Eloquent (PHP 8.4)

## Errore

```
Declaration of Illuminate\Database\Eloquent\Model::getKey() must be compatible
with Modules\Comment\Models\Contracts\CanComment::getKey(): mixed
```

## Causa

In PHP 8.4, quando una classe (`Model`) ha un return type `mixed`, l'interfaccia che la implementa **DEVE** avere lo stesso return type. Laravel 11+ ha aggiunto return type `mixed` a `Model::getKey()`.

## Perché non intercettato subito (lezione)

Durante STORY-158 Fase 1 si è aggiunto `: mixed` per soddisfare PHPStan **senza** boot Laravel né `class_exists(User::class)`. PHPStan su interfaccia isolata non riproduce il fatal LSP di PHP 8.4.

**Gate obbligatorio** dopo edit contract su modelli Eloquent:

```bash
./vendor/bin/pest tests/Unit/Modules/Comment/CanCommentContractTest.php
php artisan optimize:clear   # opcache / serve
```

## Fix

In `Modules\Comment\Models\Contracts\CanComment` — **firme compatibili con Eloquent** (PHP 8.4):

```php
public function getKey(): mixed;
public function getMorphClass(): string;
/** @param mixed $instance */
public function notify($instance): void;
```

**Regola:** L'interfaccia deve seguire le firme della classe padre (Eloquent Model) quando queste hanno return type.

## Verifica

```bash
cd laravel && ./vendor/bin/pest tests/Unit/Modules/Comment/CanCommentContractTest.php
php artisan optimize:clear   # se opcache/serve tiene vecchia interfaccia
```

## Owner contratto (2026-06-10)

- **Canon:** `Modules\Comment\Models\Contracts\CanComment`
- **Alias BC:** `Modules\User\Contracts\CanComment extends Comment\...\CanComment` (@deprecated)
- In `Modules\Comment\*`: type hint solo sul contratto Comment

Vedi: [can-comment-contract-owner.md](can-comment-contract-owner.md) · [comment-policy-blade-commentator.md](../../../concepts/comment-policy-blade-commentator.md)

## Consumer

- `BaseUser implements User\Contracts\CanComment` + `InteractsWithComments`
- `User\Models\User` eredita il contratto; consumer tenant (es. `Fixcity\Models\User`) può `implements Comment\Contracts\CanComment` per check legacy
- Vietato `use InteractsWithComments` duplicato nel child se già su `BaseUser`
