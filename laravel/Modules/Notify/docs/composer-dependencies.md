---
title: "Composer Dependencies - Modulo Notify"
type: concept
tags: [composer, dependencies]
created: 2026-07-14
updated: 2026-07-14
qmd: "composer-dependencies composer dependencies - modulo notify"
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

# Composer Dependencies - Modulo Notify

## Regola

Le dipendenze specifiche per notifiche (Firebase, FCM, Telegram, ecc.) vanno in `Modules/Notify/composer.json`, **mai** nel root `laravel/composer.json`.

## Package Notify

| Package | Versione | Uso |
|---------|----------|-----|
| `kreait/firebase-php` | ^8.1 | SDK Firebase PHP |
| `kreait/laravel-firebase` | ^7.0 | Integrazione Laravel Firebase |
| `laravel-notification-channels/fcm` | ^6.0 | Canale FCM per notifiche push |
| `laravel-notification-channels/telegram` | * | Canale Telegram |
| `irazasyed/telegram-bot-sdk` | * | SDK Telegram |
| `spatie/laravel-database-mail-templates` | * | Template email DB |
| `aws/aws-sdk-php` | * | SES, SNS |
| `symfony/postmark-mailer` | * | Postmark |

## Motivazione

- **Encapsulation**: Ogni modulo dichiara le proprie dipendenze
- **Root pulito**: `laravel/composer.json` solo per core (nwidart/laravel-modules)
- **Merge plugin**: `wikimedia/composer-merge-plugin` unisce `Modules/*/composer.json`

## Riferimenti

- [Composer Module Dependency Management](../../Xot/docs/composer-module-dependency-management.md)
- [composer-merge-plugin](../../../../docs/composer-merge-plugin.md)
