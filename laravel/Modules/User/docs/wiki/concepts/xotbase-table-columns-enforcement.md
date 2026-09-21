---
title: "XotBaseResourceTable Columns Enforcement — User Module"
type: concept
sources: []
confidence: high
created: 2026-05-07
<<<<<<< HEAD
updated: 2026-05-07
=======
updated: 2026-09-10
>>>>>>> laraxot/dev
tags: [xotbase, filament, tables, enforcement]
related:
  - "./ai-harness-user-discipline.md"
  - "./baseuser-hierarchy.md"
  - "./code-redundancy-user.md"
  - "./context-mode-user-discipline.md"
  - "./context-overflow-prevention.md"
  - "./filament-langserviceprovider-governance.md"
  - "./filament-widget-linear-crud-model-create.md"
  - "./filament-widget-resource-form-delegation.md"
---

# User Module: XotBaseResourceTable Columns

<<<<<<< HEAD
24 Table files populated with columns derived from User module Models and Passport migrations.
=======
39 Table files reviewed against User module models, local migrations and installed Passport schemas.
>>>>>>> laraxot/dev

Resources: AuthenticationLog, Client, Device, Feature, OauthAccessToken, OauthAuthCode, OauthClient, OauthPersonalAccessClient, OauthRefreshToken, PasswordReset, Permission, PersonalAccessToken, Profile, Role, SocialProvider, SocialiteUser, SsoProvider, TeamInvitation, TeamPermission, Team, TeamUser, Tenant, TenantUser, User

Key conventions applied:
- Models use `BaseModel` / `BasePivot` casts (id as string, uuid, datetime)
- SoftDeletes models include `deleted_at` (toggleable)
<<<<<<< HEAD
- Boolean columns use `->badge()`
- Passport models follow standard Laravel Passport schema
=======
- Boolean columns use `IconColumn::boolean()`
- Passport models follow standard Laravel Passport schema


## Revisione UI e schema — 2026-09-10

BMAD: problema → colonne generate non aderenti allo schema; architettura → mantenere
le Table class e il contratto `array<string, Column>`; criterio di accettazione →
identità leggibile, booleani espliciti, dettagli tecnici opzionali e nessuna scrittura durante il rendering.

Evidenze studiate: modelli in `app/Models`, migrazioni in `database/migrations`,
`filament-table-architecture.md`, history `93cff080` di `ListUsers.php`.
Le vecchie pagine non sono più l'autorità di configurazione della tabella.

- Passport: `2026_03_01_000003_create_oauth_clients_table.php` documenta
  `redirect_uris`, `grant_types` e ownership; auth code e refresh token non hanno
  `name` né timestamp di creazione nelle migrazioni. DeviceCode eredita schema e
  casts dal Passport installato (`vendor/laravel/passport/database/migrations`).
- SocialProvider usa `$schema` Sushi, non una migrazione SQL: niente slug/provider/uuid.
  SsoProvider ha display_name/type, non slug/provider.
- Feature ha `value`, non description; TeamPermission ha `permission` e `name`, non permission_id.
- Device ha device/platform/browser e flag di tipo, non user_id/ip; TenantUser non ha permissions.
- CustomersTable segue `Quaeris/Models/Customer` e la migrazione customers:
  email/mobile_phone, mentre TenantsTable segue il modello Tenant.
- BaseProfilesTable deve mostrare un placeholder per user assente: eliminare
  la generazione fake di email e gli update dal callback della colonna.
- Token e refresh token non sono dati utili alla lista amministrativa e vengono esclusi.
- Conservare tutte le chiavi stringa, usare IconColumn boolean per i flag,
  ricerca su identità reali, audit e identificatori tecnici nascosti ma selezionabili.

QMD search tentato: runtime bloccato dal binding better-sqlite3 (ABI 127/147).
Fallback: lettura diretta delle note e ricerca locale con rg.


Verifica: 39 file superano PHP lint e PHPStan con configurazione esistente; PHPMD
con `Modules/User/phpmd.ruleset.xml` passa. Bootstrap Laravel e istanziazione delle
39 classi: chiavi stringa uguali al nome della colonna, nessuna colonna credenziale.
PHPInsights indisponibile (binario assente e comando artisan insights non definito).
>>>>>>> laraxot/dev
