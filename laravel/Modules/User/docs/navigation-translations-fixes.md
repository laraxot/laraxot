<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
---
title: "Correzioni Traduzioni Navigation - Gennaio 2026"
type: concept
tags: [navigation, translations, fixes]
created: 2026-07-14
updated: 2026-07-14
qmd: "navigation-translations-fixes correzioni traduzioni navigation - gennaio 2026"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./2fa-guide.md"
  - "./2fa.md"
  - "./accessor-delegation-pattern.md"
  - "./actions-path-convention-1.md"
  - "./actions-path-convention-2.md"
  - "./actions-path-convention.md"
---

# Correzioni Traduzioni Navigation - Gennaio 2026

## Data Intervento
**2026-01-22** - Sistemazione completa traduzioni navigation secondo regole DRY + KISS

## Problema Identificato

Tutti i file di traduzione che contengono la stringa `.navigation` indicano traduzioni incomplete/placeholder che devono essere sistemate immediatamente.

## File Corretti

### 1. `lang/it/oauth_client.php`
**Problema**: Placeholder `.navigation` in label, group e icon
**Correzione**: 
- `name`: "OAuth Client"
- `plural`: "OAuth Clients"
- `label`: "Client OAuth"
- `group`: "API" con descrizione
- `icon`: "heroicon-o-key"
- `sort`: 89

### 2. `lang/it/tenant_user.php`
**Problema**: Placeholder `.navigation` in label, group e icon
**Correzione**:
- `name`: "Utente Tenant"
- `plural`: "Utenti Tenant"
- `label`: "Utenti Tenant"
- `group`: "Tenants" con descrizione
- `icon`: "heroicon-o-building-office"
- `sort`: 87

### 3. `lang/it/team_user.php`
**Problema**: Placeholder `.navigation` in label, group e icon
**Correzione**:
- `name`: "Utente Team"
- `plural`: "Utenti Team"
- `label`: "Utenti Team"
- `group`: "Teams" con descrizione
- `icon`: "heroicon-o-user-group"
- `sort`: 65

### 4. `lang/it/sso_provider.php`
**Problema**: Placeholder `.navigation` in label, group e icon
**Correzione**:
- `name`: "Provider SSO"
- `plural`: "Provider SSO"
- `label`: "Provider SSO"
- `group`: "Authentication" con descrizione
- `icon`: "heroicon-o-identification"
- `sort`: 3

### 5. `lang/it/authentication_log.php`
**Problema**: Placeholder `.navigation` in group e icon
**Correzione**:
- `name`: "Log Autenticazione"
- `plural`: "Log Autenticazione"
- `label`: "Log Autenticazione"
- `group`: "Sicurezza" con descrizione
- `icon`: "heroicon-o-shield-check"
- `sort`: 3

### 6. `lang/it/password_reset.php`
**Problema**: Placeholder `.navigation` in group e icon
**Correzione**:
- `name`: "Reset Password"
- `plural`: "Reset Password"
- `label`: "Reset Password"
- `group`: "Sicurezza" con descrizione
- `icon`: "heroicon-o-key"
- `sort`: 4

### 7. `lang/it/oauth_auth_code.php`
**Problema**: Placeholder `.navigation` in label, group e icon
**Correzione**:
- `name`: "OAuth Authorization Code"
- `plural`: "OAuth Authorization Codes"
- `label`: "Authorization Code OAuth"
- `group`: "API" con descrizione
- `icon`: "heroicon-o-code-bracket"
- `sort`: 31

### 8. `lang/it/oauth_access_token.php`
**Problema**: Placeholder `.navigation` in group e icon
**Correzione**:
- `name`: "OAuth Access Token"
- `plural`: "OAuth Access Tokens"
- `label`: "Access Token OAuth"
- `group`: "API" con descrizione
- `icon`: "heroicon-o-key"
- `sort`: 5

### 9. `lang/it/team_invitation.php`
**Problema**: Placeholder `.navigation` in label, group e icon
**Correzione**:
- `name`: "Invito Team"
- `plural`: "Inviti Team"
- `label`: "Inviti Team"
- `group`: "Teams" con descrizione
- `icon`: "heroicon-o-envelope"
- `sort`: 34

### 10. `lang/it/socialite_user.php`
**Problema**: Placeholder `.navigation` in label, group e icon
**Correzione**:
- `name`: "Autenticazione Social"
- `plural`: "Autenticazioni Social"
- `label`: "Autenticazioni Social"
- `group`: "Authentication" con descrizione
- `icon`: "heroicon-o-user"
- `sort`: 89

### 11. `lang/it/oauth_refresh_token.php`
**Problema**: Placeholder `.navigation` in label, group e icon
**Correzione**:
- `name`: "OAuth Refresh Token"
- `plural`: "OAuth Refresh Tokens"
- `label`: "Refresh Token OAuth"
- `group`: "API" con descrizione
- `icon`: "heroicon-o-arrow-path"
- `sort`: 27

## Struttura Corretta Applicata

Tutte le traduzioni seguono ora la struttura espansa completa:

```php
'navigation' => [
    'name' => 'Nome Singolare',
    'plural' => 'Nome Plurale',
    'label' => 'Etichetta Navigazione',
    'group' => [
        'name' => 'Nome Gruppo',
        'description' => 'Descrizione del gruppo',
    ],
    'sort' => 10,
    'icon' => 'heroicon-o-icon-name',
],
```

## Raggruppamento Logico

### Gruppo "API"
- OAuth Client
- OAuth Authorization Code
- OAuth Access Token
- OAuth Refresh Token
- OAuth Personal Access Client (già corretto)

### Gruppo "Authentication"
- SSO Provider
- Socialite User

### Gruppo "Sicurezza"
- Authentication Log
- Password Reset

### Gruppo "Teams"
- Team User
- Team Invitation

### Gruppo "Tenants"
- Tenant User
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
# Correzioni Traduzioni Navigation - Modulo User

## Data Intervento
**2025-08-07** - Sistemazione traduzioni secondo regole DRY + KISS

## Problemi Identificati

### File: `lang/it/device.php`
**Problema**: Chiavi hardcoded con ".navigation" nella sezione navigation
```php
// ❌ PRIMA (Problematico)
'navigation' => [
    'label' => 'device.navigation',
    'group' => 'device.navigation', 
    'icon' => 'device.navigation',
]
```

**Soluzione**: Traduzioni localizzate e appropriate
```php
// ✅ DOPO (Corretto)
'navigation' => [
    'label' => 'Dispositivi',
    'group' => 'Sicurezza',
    'icon' => 'heroicon-o-device-phone-mobile',
]
```

### File: `lang/it/permission.php`
**Problema**: Chiavi hardcoded con ".navigation" nella sezione navigation
```php
// ❌ PRIMA (Problematico)
'navigation' => [
    'label' => 'permission.navigation',
    'group' => 'permission.navigation',
    'icon' => 'permission.navigation',
]
```

**Soluzione**: Traduzioni localizzate e appropriate
```php
// ✅ DOPO (Corretto)
'navigation' => [
    'label' => 'Permessi',
    'group' => 'Sicurezza', 
    'icon' => 'heroicon-o-shield-check',
]
```
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
---
title: "Correzioni Traduzioni Navigation - Gennaio 2026"
type: concept
tags: [navigation, translations, fixes]
created: 2026-07-14
updated: 2026-07-14
qmd: "navigation-translations-fixes correzioni traduzioni navigation - gennaio 2026"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./2fa-guide.md"
  - "./2fa.md"
  - "./accessor-delegation-pattern.md"
  - "./actions-path-convention-1.md"
  - "./actions-path-convention-2.md"
  - "./actions-path-convention.md"
---

# Correzioni Traduzioni Navigation - Gennaio 2026

## Data Intervento
**2026-01-22** - Sistemazione completa traduzioni navigation secondo regole DRY + KISS

## Problema Identificato

Tutti i file di traduzione che contengono la stringa `.navigation` indicano traduzioni incomplete/placeholder che devono essere sistemate immediatamente.

## File Corretti

### 1. `lang/it/oauth_client.php`
**Problema**: Placeholder `.navigation` in label, group e icon
**Correzione**: 
- `name`: "OAuth Client"
- `plural`: "OAuth Clients"
- `label`: "Client OAuth"
- `group`: "API" con descrizione
- `icon`: "heroicon-o-key"
- `sort`: 89

### 2. `lang/it/tenant_user.php`
**Problema**: Placeholder `.navigation` in label, group e icon
**Correzione**:
- `name`: "Utente Tenant"
- `plural`: "Utenti Tenant"
- `label`: "Utenti Tenant"
- `group`: "Tenants" con descrizione
- `icon`: "heroicon-o-building-office"
- `sort`: 87

### 3. `lang/it/team_user.php`
**Problema**: Placeholder `.navigation` in label, group e icon
**Correzione**:
- `name`: "Utente Team"
- `plural`: "Utenti Team"
- `label`: "Utenti Team"
- `group`: "Teams" con descrizione
- `icon`: "heroicon-o-user-group"
- `sort`: 65

### 4. `lang/it/sso_provider.php`
**Problema**: Placeholder `.navigation` in label, group e icon
**Correzione**:
- `name`: "Provider SSO"
- `plural`: "Provider SSO"
- `label`: "Provider SSO"
- `group`: "Authentication" con descrizione
- `icon`: "heroicon-o-identification"
- `sort`: 3

### 5. `lang/it/authentication_log.php`
**Problema**: Placeholder `.navigation` in group e icon
**Correzione**:
- `name`: "Log Autenticazione"
- `plural`: "Log Autenticazione"
- `label`: "Log Autenticazione"
- `group`: "Sicurezza" con descrizione
- `icon`: "heroicon-o-shield-check"
- `sort`: 3

### 6. `lang/it/password_reset.php`
**Problema**: Placeholder `.navigation` in group e icon
**Correzione**:
- `name`: "Reset Password"
- `plural`: "Reset Password"
- `label`: "Reset Password"
- `group`: "Sicurezza" con descrizione
- `icon`: "heroicon-o-key"
- `sort`: 4

### 7. `lang/it/oauth_auth_code.php`
**Problema**: Placeholder `.navigation` in label, group e icon
**Correzione**:
- `name`: "OAuth Authorization Code"
- `plural`: "OAuth Authorization Codes"
- `label`: "Authorization Code OAuth"
- `group`: "API" con descrizione
- `icon`: "heroicon-o-code-bracket"
- `sort`: 31

### 8. `lang/it/oauth_access_token.php`
**Problema**: Placeholder `.navigation` in group e icon
**Correzione**:
- `name`: "OAuth Access Token"
- `plural`: "OAuth Access Tokens"
- `label`: "Access Token OAuth"
- `group`: "API" con descrizione
- `icon`: "heroicon-o-key"
- `sort`: 5

### 9. `lang/it/team_invitation.php`
**Problema**: Placeholder `.navigation` in label, group e icon
**Correzione**:
- `name`: "Invito Team"
- `plural`: "Inviti Team"
- `label`: "Inviti Team"
- `group`: "Teams" con descrizione
- `icon`: "heroicon-o-envelope"
- `sort`: 34

### 10. `lang/it/socialite_user.php`
**Problema**: Placeholder `.navigation` in label, group e icon
**Correzione**:
- `name`: "Autenticazione Social"
- `plural`: "Autenticazioni Social"
- `label`: "Autenticazioni Social"
- `group`: "Authentication" con descrizione
- `icon`: "heroicon-o-user"
- `sort`: 89

### 11. `lang/it/oauth_refresh_token.php`
**Problema**: Placeholder `.navigation` in label, group e icon
**Correzione**:
- `name`: "OAuth Refresh Token"
- `plural`: "OAuth Refresh Tokens"
- `label`: "Refresh Token OAuth"
- `group`: "API" con descrizione
- `icon`: "heroicon-o-arrow-path"
- `sort`: 27

## Struttura Corretta Applicata

Tutte le traduzioni seguono ora la struttura espansa completa:

```php
'navigation' => [
    'name' => 'Nome Singolare',
    'plural' => 'Nome Plurale',
    'label' => 'Etichetta Navigazione',
    'group' => [
        'name' => 'Nome Gruppo',
        'description' => 'Descrizione del gruppo',
    ],
    'sort' => 10,
    'icon' => 'heroicon-o-icon-name',
],
```

## Raggruppamento Logico

### Gruppo "API"
- OAuth Client
- OAuth Authorization Code
- OAuth Access Token
- OAuth Refresh Token
- OAuth Personal Access Client (già corretto)

### Gruppo "Authentication"
- SSO Provider
- Socialite User

### Gruppo "Sicurezza"
- Authentication Log
- Password Reset

### Gruppo "Teams"
- Team User
- Team Invitation

### Gruppo "Tenants"
- Tenant User
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
=======
>>>>>>> laraxot/dev

## Regole Applicate

### DRY (Don't Repeat Yourself)
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
- [Regole Traduzioni Laraxot](../Xot/docs/translation-rules.md)
- [Standard Qualità Traduzioni](../<nome progetto>/docs/translation-quality-standards.md)
- [Documentazione Modulo User](README.md)
- Eliminata duplicazione di chiavi placeholder
- Raggruppamento logico coerente
- Icone standard Heroicons per consistenza

### KISS (Keep It Simple, Stupid)
- Traduzioni dirette e chiare in italiano
- Nomi descrittivi e intuitivi
- Struttura semplice e leggibile

## Validazione

- ✅ Nessuna chiave con `.navigation` rimasta
- ✅ Tutte le traduzioni appropriate e localizzate
- ✅ Icone standard Heroicons
- ✅ Raggruppamento logico coerente
- ✅ Struttura espansa completa per tutte le navigation

## Collegamenti

- [Filosofia Traduzioni Laraxot](../../xot/docs/translation-philosophy.md)
- [Standard Traduzioni](../../xot/docs/translation-standards.md)
- [Documentazione Modulo User](./readme.md)
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
- Eliminata duplicazione di chiavi non tradotte
- Raggruppamento logico sotto "Sicurezza" per coerenza
- Icone standard Heroicons per consistenza

### KISS (Keep It Simple, Stupid)
- Traduzioni dirette e chiare
- Nomi descrittivi e intuitivi
- Struttura semplice e leggibile

## Benefici Ottenuti

1. **Localizzazione Corretta**: Traduzioni in italiano appropriato
2. **Coerenza UI**: Raggruppamento logico sotto "Sicurezza"
3. **Manutenibilità**: Eliminazione di chiavi hardcoded
4. **Standard Compliance**: Rispetto delle regole di traduzione Laraxot

## Validazione

- ✅ Nessuna chiave hardcoded con ".navigation"
- ✅ Traduzioni appropriate e localizzate
- ✅ Icone standard Heroicons
- ✅ Raggruppamento logico coerente

## Collegamenti

- [Audit Generale Traduzioni Navigation](../../docs/navigation-translations-audit.md)
- [Regole Traduzioni Laraxot](../xot/docs/translation-rules.md)
- [Standard Qualità Traduzioni](../<nome progetto>/docs/translation-quality-standards.md)
- [Documentazione Modulo User](readme.md)
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
- [Regole Traduzioni Laraxot](../Xot/docs/translation-rules.md)
- [Standard Qualità Traduzioni](../<nome progetto>/docs/translation-quality-standards.md)
- [Documentazione Modulo User](README.md)
- Eliminata duplicazione di chiavi placeholder
- Raggruppamento logico coerente
- Icone standard Heroicons per consistenza

### KISS (Keep It Simple, Stupid)
- Traduzioni dirette e chiare in italiano
- Nomi descrittivi e intuitivi
- Struttura semplice e leggibile

## Validazione

- ✅ Nessuna chiave con `.navigation` rimasta
- ✅ Tutte le traduzioni appropriate e localizzate
- ✅ Icone standard Heroicons
- ✅ Raggruppamento logico coerente
- ✅ Struttura espansa completa per tutte le navigation

## Collegamenti

- [Filosofia Traduzioni Laraxot](../../xot/docs/translation-philosophy.md)
- [Standard Traduzioni](../../xot/docs/translation-standards.md)
- [Documentazione Modulo User](./readme.md)
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
=======
>>>>>>> laraxot/dev

## Note Tecniche

- Mantenuta la struttura espansa esistente
- Preservata la sintassi array breve `[]`
- Rispettato il `declare(strict_types=1);`
- Icone scelte per semantica appropriata
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
- Sort order allineato con i Resource corrispondenti

*Intervento completato il: 2026-01-22*
*Conforme alle regole DRY + KISS*
=======

*Intervento completato il: 2025-08-07*
*Conforme alle regole DRY + KISS*
>>>>>>> 60a2c9a9 (.)
=======

*Intervento completato il: 2025-08-07*
*Conforme alle regole DRY + KISS*
=======
- Sort order allineato con i Resource corrispondenti

*Intervento completato il: 2026-01-22*
*Conforme alle regole DRY + KISS*
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
=======
- Sort order allineato con i Resource corrispondenti

*Intervento completato il: 2026-01-22*
*Conforme alle regole DRY + KISS*
>>>>>>> laraxot/dev
