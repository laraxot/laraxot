---
title: "Ponytail audit — Notify (over-engineering)"
type: concept
tags: [ponytail, audit, over, engineering]
created: 2026-07-14
updated: 2026-07-14
qmd: "ponytail-audit-over-engineering ponytail audit — notify (over-engineering)"
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

# Ponytail audit — Notify (over-engineering)

**Ultimo run:** 2026-07-01  
**Remediation run #4:** consolidamento SMS su driver canonico `smsfactor`

| # | Azione | Stato |
|---|--------|-------|
| N1 | 8 action SMS inattive + test → `.php.bak` | ✅ |
| N2 | 3 varianti Agiletelecom → `.php.bak` | ✅ |
| N3 | `Contracts\SmsActionContract` root (duplicato) | ✅ eliminato |
| N4 | `SmsProviderContract` (0 impl) | ✅ eliminato |
| N6 | `Modules.bak/` | ✅ assente |
| N7 | `_bmad/`, `.planning/` sotto Notify | ✅ assenti |
| N8 | `scheduleNotification`: `SendScheduledPushNotification::dispatch()->delay()` | ✅ (no regressione) |

**Modulo:** notifiche multi-canale.  
**Hub:** [../../../../docs/audit/ponytail-audit.md](../../../../docs/audit/ponytail-audit.md)  
**Remediation:** [../../../../docs/project/ponytail-audit-remediation.md](../../../../docs/project/ponytail-audit-remediation.md)  
**GitHub monorepo:** [Issue #221](https://github.com/laraxot/base_predict_fila5/issues/221) · [Discussion #222](https://github.com/laraxot/base_predict_fila5/discussions/222) · [Discussion #228](https://github.com/laraxot/base_predict_fila5/discussions/228)

## Scopo business

Notify invia messaggi su canali configurati (email, SMS, push, …). Il driver attivo deve essere **uno per ambiente**; molti provider in codice senza uso in produzione è complessità senza valore.

## Driver SMS canonico

**`smsfactor`** — SSoT da `config/sms.php` (`default => smsfactor`).

Pattern attivo:

- `app/Contracts/SMS/SmsActionContract.php`
- `app/Factories/SmsActionFactory.php` → `SendSmsFactorSMSAction`
- `app/Actions/SMS/SendSmsFactorSMSAction.php`
- `app/Datas/SMS/SmsFactorData.php`
- `app/Enums/SmsDriverEnum` → solo case `SMSFACTOR`

Utility mantenute: `NormalizePhoneNumberAction`, `FormatSmsMessageAction`.

## File archiviati (`.php.bak`)

### Actions SMS inattive

- `SendTwilioSMSAction`, `SendNexmoSMSAction`, `SendPlivoSMSAction`, `SendGammuSMSAction`
- `SendNetfunSMSAction`, `SendAgiletelecomSMSAction`, `SendAgiletelecomSMSv1Action`, `SendAgiletelecomSMSv2Action`
- `NetfunSendAction` (duplicato logica netfun, sostituito da factory)

### Data class driver inattivi

- `TwilioData`, `NexmoData`, `PlivoData`, `GammuData`, `AgiletelecomData`

### Test correlati

- Tutti i `tests/Unit/Actions/SMS/Send{Driver}*Test.php` per driver rimossi
- `NetfunSendActionTest`, `TwilioDataTest`

### Filament test-only

- `SendNetfunSmsPage` (pagina dedicata netfun; `SendSmsPage` resta con enum a singolo driver)

## File eliminati

- `app/Contracts/SmsActionContract.php` (duplicato di `Contracts/SMS/SmsActionContract`)
- `app/Contracts/SmsProviderContract.php` (0 implementazioni)

## Refactor canali

- `app/Channels/NetfunChannel` → usa `SmsActionFactory` (non più `SendNetfunSMSAction`)
- `app/Notifications/Channels/NetfunChannel` → usa `SmsActionFactory` (non più `NetfunSendAction`)

## ⛔ Fuori perimetro (non tagliare)

| Area | Motivo |
|------|--------|
| `app/Models/Policies/*Policy.php` | Contratto Laravel/Filament per modello |

## Collegamenti

- [wiki/decisions/sms-actions-consolidation-.md.md](./wiki/decisions/sms-actions-consolidation-.md.md)
- [provider-actions-architecture.md](./provider-actions-architecture.md)
- [Xot Notify hub](../../Xot/docs/ponytail-audit-over-engineering.md)
