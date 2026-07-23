---
title: "PHPStan Notification Action Contract"
type: concept
tags: [phpstan, notification, action, contract]
created: 2026-07-14
updated: 2026-07-14
qmd: "phpstan-notification-action-contract phpstan notification action contract"
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

# PHPStan Notification Action Contract

## Problem

Il cluster PHPStan di `Notify` nasce quando action, job e service non condividono lo stesso contratto applicativo e puntano a model o payload troppo generici.

## Active Rule

- usare il model reale del modulo: `Modules\Notify\Models\Notification`
- esporre un entrypoint coerente `execute()` per le actions invocate da job/service
- tipizzare canali come `array<int, string>`
- tipizzare payload come `array<string, mixed>`
- portare `MobilePushNotification::toCloudMessage()` al tipo Kreait realmente accettato dal channel

## Result

Questo evita errori `class.notFound`, `method.notFound`, `argument.type` e riduce il rumore nel cluster Notify senza toccare la configurazione di PHPStan.
