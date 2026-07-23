---
title: "Notify Services/Support -> Actions migration"
type: concept
tags: [notify, actions, queueable-action, services, refactor]
created: 2026-07-13
updated: 2026-07-13
qmd: "Notify app Services Support converted to QueueableAction migration mapping"
issues:
related:
  - "./claude-audit-static.md"
  - "./code-redundancy-notify.md"
  - "./composer-root-minimal-nwidart.md"
  - "./context-overflow-prevention.md"
  - "./enum-standards.md"
  - "./llm-wiki-governance.md"
  - "./method-name-homonyms.md"
  - "./module-root-uppercase-folders-archive.md"
---

# Notify `app/Services` -> `app/Actions` migration

## Stato

`Modules/Notify/app/Services` non conteneva piu` codice autoloadabile attivo al
momento della migrazione: ogni logica era gia` stata spostata in `app/Actions`
da un passaggio precedente. `Modules/Notify/app/Support` era gia` vuoto
(eliminato monorepo-wide, vedi `no-app-support-queueable-actions.md`).

## Mapping

| Legacy `app/Services` (classe) | Destinazione `app/Actions` | Note |
|------|----------------------------|------|
| `MailService` (facade `send()`/`try()`) | `Actions\Mail\SendMailAction`, `Actions\Mail\TryMailAction` | dispatch dinamico per driver a `Actions\Mail\Engines\{Driver}\Send{Driver}MailAction` |
| `MailEngines\DuocircleEngine::send()` | `Actions\Mail\Engines\Duocircle\SendDuocircleMailAction` | WIP (throw RuntimeException) |
| `MailEngines\DuocircleEngine::try()` | `Actions\Mail\Engines\Duocircle\TryDuocircleMailAction` | IMAP via `Webklex\PHPIMAP` (pkg non installato) |
| `PushNotificationService` (multi-method) | `Actions\PushNotificationAction` + `Actions\PushNotificationPlatformDelivery` + `Actions\SendPushNotificationAction` | 1 Action per public method (`sendToDevice`, `sendToDevices`, `sendToTopic`, `sendToAll`, `scheduleNotification`, `sendWithTemplate`, `sendWithTargeting`) |

## Pulizia file morti

I file residui non autoloadabili in `app/Services` sono stati archiviati con
suffisso `.old` (convenzione repo per questo modulo; storia in git preservata
via `git mv`, mai `git rm`):

- `app/Services/PushNotificationService.php` -> `PushNotificationService.php.old`
- `app/Services/MailService.to_action` -> `MailService.to_action.old`
- `app/Services/MailEngines/DuocircleEngine.test` -> `DuocircleEngine.test.old`
- `app/Services/MailEngines/duocircleengine.test` -> `duocircleengine.test.old`

Rimane `app/Services/.gitkeep` a preservare la directory (archivio).

Callers repo-wide di `Modules\Notify\Services\*`: 0 (solo riferimenti in `docs/*.md`).

## Aggiornamento 2026-07-16 — pulizia residui

Verifica finale monorepo-wide (`laravel/Modules/*`, `laravel/Themes/*`,
`laravel/app/`): l'unico riferimento a codice `Modules\Notify\Services\*`
rimasto era in `app/Facades/NotificationFacade.php`, che importava la classe
gia` eliminata `Modules\Notify\Services\NotificationService` e puntava a un
accessor `notify.service` mai registrato in nessun provider. Facade morta e
non referenziata (nessun uso repo-wide, `SendNotificationAction` usa il facade
nativo `Illuminate\Support\Facades\Notification`): archiviata come
`app/Facades/NotificationFacade.php.bak` (convenzione archivio, mai `git rm`).

Riferimenti a `Modules\Notify\Services\` in codice PHP dopo l'archiviazione: 0
(solo `docs/*.md` e file `.bak`).

Aggiunto il trait `Spatie\QueueableAction\QueueableAction` alle 6 Action che ne
erano prive (flag di `audit-queueable-action-trait.sh`):
`Actions\SMS\FormatSmsMessageAction`, `Actions\SMS\NormalizePhoneNumberAction`,
`Actions\SMS\SendAgiletelecomSMSAction`, `Actions\SMS\SendAgiletelecomSMSv1Action`,
`Actions\SMS\SendAgiletelecomSMSv2Action`, `Actions\SmtpMailSendAction`.
Dopo la fix l'audit non riporta piu` alcuna Action Notify senza trait.

## Quality gate (pre-esistenti, non introdotti da questa migrazione)

- **PHPStan**: errori presenti in `app/Actions/Mail/*` (package `webklex/php-imap`
  non installato -> `class.notFound`; dispatch dinamico `app($class)->execute()`
  -> `method.nonObject`). Sono negli Action gia` creati in precedenza, non nel
  codice Services rimosso.
- **pest**: harness ok (singola suite unit passa); la suite completa di Notify
  (205 file) supera il timeout di boot Laravel, lentenza pre-esistente.
- **phpmd**: tool non presente in questo repo (`bashscripts/tools/ensure_phpmd_phar.sh`
  e `tools/phpmd.phar` assenti) -> non eseguibile.
