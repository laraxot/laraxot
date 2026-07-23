---
title: "PHPStan: Firebase e Mobile Push Notification"
type: concept
tags: [phpstan, firebase, mobile, push]
created: 2026-07-14
updated: 2026-07-14
qmd: "phpstan-firebase-mobile-push phpstan: firebase e mobile push notification"
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

# PHPStan: Firebase e Mobile Push Notification

## Contesto

Il modulo Notify usa Kreait Firebase e FCM per push notification. Le classi Kreait non sono sempre analizzate da PHPStan.

## Correzioni applicate

### 1. Contratto MobilePushNotification

Creato `app/Contracts/MobilePushNotification.php` con metodi `toArray()` e `toCloudMessage()` per type safety.

### 2. SendPushNotification e SendPushNotificationPage

- Uso di `class_exists()` e nomi di classe come stringhe per Kreait
- `@phpstan-ignore-next-line method.nonObject` per catene su `CloudMessage` ottenute dinamicamente

### 3. FirebaseAndroidNotification

- `class_exists()` per Kreait e FcmChannel
- `@phpstan-ignore-next-line method.nonObject` per `withAndroidConfig()` e `withData()`

### 4. MailTemplateVersion

- Rimosso import inesistente `MailTemplateVersionFactory`
- PHPDoc `factory()` aggiornato a `\Illuminate\Database\Eloquent\Factories\Factory<static>`

## Pattern

Per dipendenze opzionali (Kreait, FCM): usare `class_exists()` e `@phpstan-ignore` solo dove necessario.
