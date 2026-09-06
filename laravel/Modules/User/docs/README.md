---
title: "User — L'Identità del Sistema"
description: "Modulo identità: autenticazione, autorizzazioni, ruoli, multi-tenancy, OAuth2 e SSO"
module: "User"
alias: "user"
version: "1.0.0"
priority: 0
active: true
status: "core-identity"
author: "Team Laraxot"
license: "Proprietary"
php_version: "^8.1"
core_version: "10.0"
dependencies: ["Xot", "Tenant"]
extends: []
extended_by: 40
documentation_date: "2026-05-27"
---

# User — L'Identità del Sistema

## Scopo

User è il modulo identità della famiglia Laraxot. Espone quattro classi base astratte (`BaseUser`, `BaseProfile`, `BaseTeam`, `BaseTenant`) che il progetto host estende tramite `XotData`. Implementa una volta sola login, ruoli, multi-tenancy, OAuth2 e SSO — funzionalità che ogni applicazione tende a reinventare con scorciatoie diverse.

## Religione

User è trattato come **il corpo identitario** del sistema. La sua religione si esprime in:

- **"Un solo BaseUser con UUID"**: la tabella `users` ha `id` stringa da 36 caratteri, `is_active`, `is_otp`, `password_expires_at`, `lang`, `current_team_id`. Il campo `type` alimenta la STI di `Parental\HasChildren`.
- **"L'identità si estende per ereditarietà"**: `BaseUser` → `User`, `BaseProfile` → `Profile`, `BaseTeam` → `Team`, `BaseTenant` → `Tenant`. Il progetto host estende, non riscrive.
- **"Ogni scrittura passa per Action"**: `CreateUserAction`, `UpdateUserAction`, `DeleteUserAction`, `SendOtpByUserAction`, `LoginUserAction`, `RevokeAllUserTokensAction`.
- **"Il database identità è separato"**: ogni modello usa `protected $connection = 'user'`.
- **"Il token scade, il log resta"**: `AuthenticationLog` è polimorfico (`uuidMorphs('authenticatable')`) perché non solo gli utenti fanno login.
- **"Password policy è sacra"**: configurabile con `min`, `expires_in`, `otp_length`, `mixedCase`, `symbols`, `uncompromised`.

## Filosofia

Le classi base sono astratte perché **l'identità non appartiene a nessun progetto in particolare**. Il campo `type` esiste perché l'identità cambia forma senza cambiare tabella. Il log di autenticazione è polimorfico perché **non solo gli utenti fanno login**.

La filosofia è **identità come contratto**: `UserContract`, `TeamContract`, `TenantContract`, `HasAuthentications`, `HasShieldPermissions` in `app/Contracts` sono il confine; `BaseUser`, `BaseProfile`, `BaseTeam`, `BaseTenant` sono il punto di estensione; `XotData` è il registro che dice al resto dell'ecosistema quale classe concreta usare.

## Politica

- **Separazione dei database**: connessione `user` per identità, connessione `mysql`/`pgsql` per business. Questa separazione è politica di sicurezza e architettura.
- **Ruoli Spatie per team**: `Role` ha `team_id`, rendendo i permessi contestuali al team, non globali.
- **Socialite e SSO**: Auth0, Google, GitHub, Microsoft. La registrazione social si disattiva con `socialite.registration = false` e si restringe con `socialite.domain_allowlist`.
- **Passport completo**: access_token 15 giorni, refresh_token 30 giorni, personal_access_token 6 mesi. Scopes `view-user`, `core-technicians`.
- **Password confirmation**: `DeleteUserAction` richiede password di conferma e fa `logout()` prima di `delete()`.
- **Due factor**: OTP con `SendOtpByUserAction`, `VerifyOtpHashAction`, regola `CheckOtpExpiredRule`.

## Zen

> **"Il token scade, il log resta, l'utente è sempre lo stesso id."**

Lo Zen di User è la **persistente identità**. Le sessioni scadono, i token si rinnovano, i dispositivi cambiano, ma l'utente resta il suo UUID immutabile. Il sistema riconosce chi sei non dalla sessione, ma dall'id.

## Perché esiste

Ogni applicazione ha bisogno di login, ruoli e multi-tenancy. User esiste perché **non si dovrebbe mai riscrivere l'identità da zero**. Le 40+ dipendenze su User dimostrano che è il modulo più strategico dell'ecosistema. Senza User, nessun altro modulo può funzionare.

## Superpoteri

- **Login**: `Login` estende `XotBaseLogin`, con `LoginWidget`, `SocialLoginWidget`, `RegisterWidget`, `ForgotPasswordWidget`, `ResetPasswordWidget`
- **Socialite**: `/admin/login/{provider}`, `/auth/social/{provider}`, `/sso/{provider}/callback` con `ProcessCallbackController`
- **Passport**: server OAuth2 completo con cluster Filament dedicato
- **Authentication Log**: tracciamento IP, user agent, geo-localizzazione
- **Device tracking**: `Jenssegers\Agent` per identificare mobile/desktop
- **Commands**: `user:user-list`, `user:tenant-list`, `user:super-admin`, `user:passport:install`, `user:change-password`

## Cosa Mancherebbe (Gap Analysis)

| Gap | Severità | Suggerimento |
|-----|----------|--------------|
| Nessun sistema di impersonation (login as user) | Alta | Aggiungere `ImpersonateAction` e `ImpersonateSession` |
| Manca audit trail per cambiamenti profilo | Alta | Estendere `Activity` per tracciare modifiche profile |
| Nessun modulo di backup/emergency access | Media | Creare `EmergencyAccess` con recovery codes |
| Missing WebAuthn/FIDO2 support | Media | Aggiungere `WebAuthnProvider` come alternativa OTP |
| Nessun sistema di organization/accounting groups | Media | Estendere `Team` con gerarchie di organizzazioni |
| Manca session management granulare | Bassa | Lista sessioni attive con revoke singolo |
| Nessun modulo di passwordless auth | Bassa | Aggiungere `MagicLinkAuth` come strategia alternativa |
| Socialite provider catalog non aggiornato | Bassa | Verificare aggiornamento 14 provider |

---

*Documento generato secondo le convenzioni del progetto — modulo `User` — data 2026-05-27*