---
title: "Debito D14 — Wave3 Email/WhatsApp/Platform: Event SP + PHPStan post-enable"
type: how-to
tags: [technical-debt, email, whatsapp, platform, xotbase, phpstan]
created: 2026-07-24
updated: 2026-07-24
qmd: "debt wave3 email whatsapp platform eventserviceprovider phpstan enable"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./gestionale-technical-debt.md"
  - "./debt-replica-auth-hr-enable.md"
  - "../Themes/docs/gc-modules-runtime-matrix.md"
---

# Debito D14 — Wave3 enabled: chiudere reshape incompleto

## Perché

Email, WhatsApp, Platform (e affini Wave3) sono **enabled** in `modules_statuses.json` dopo reshape, ma i docs di import segnalano ancora:

- `EventServiceProvider` potenzialmente ancora `Illuminate\Foundation\Support\Providers\EventServiceProvider` invece di `XotBaseEventServiceProvider`
- BaseModel / factories / Pest incompleti
- Baseline PHPStan storica (decine/centinaia di errori) **non** rieseguita post-enable

Enabled senza gate = debito runtime (eventi doppi, type errors in produzione).

## Come risolvere — checklist per **ogni** modulo `{Email,WhatsApp,Platform}`

### 1. Provider

```bash
cd laravel
rg -n "class EventServiceProvider|extends " Modules/Email/app/Providers/EventServiceProvider.php
rg -n "class EventServiceProvider|extends " Modules/WhatsApp/app/Providers/EventServiceProvider.php
rg -n "class EventServiceProvider|extends " Modules/Platform/app/Providers/EventServiceProvider.php
```

Target:

```php
use Modules\Xot\Providers\XotBaseEventServiceProvider;
class EventServiceProvider extends XotBaseEventServiceProvider {}
```

Stesso audit per Route → `XotBaseRouteServiceProvider` (di solito già OK).

### 2. PHPStan L10 scoped (evidenza nuova, non baseline antica)

```bash
./vendor/bin/phpstan analyse Modules/Email --memory-limit=-1
./vendor/bin/phpstan analyse Modules/WhatsApp --memory-limit=-1
./vendor/bin/phpstan analyse Modules/Platform --memory-limit=-1
```

Classificare errori:

| Classe | Azione |
|---|---|
| User sbagliato (`App\Models\User`) | Allineare a `Modules\User\Models\User` (pattern Auth) |
| property.notFound Eloquent | `@property` o accessor tipizzati — non ignore ciechi |
| dipendenze assenti | Soft-guard o require consapevole |
| rumore ignore `@mixin` unmatched | Accettabile se **solo** quello |

### 3. Models / factories

- BaseModel → XotBaseModel del modulo (o pattern modulo)
- Factory per ogni model persistibile (parity N=N)
- Seeders: `*Factory::new()` non `factory()` legacy

### 4. Pest minimo

Almeno ServiceProviderTest + ModelsTest (come Customer).  
Poi D6/D13 per CI.

### 5. Docs modulo

Aggiornare `import-status.md`: checkbox Event XotBase, PHPStan 0 reali, Pest.

## Ordine consigliato

1. Platform (settings/company — impatto trasversale)
2. Email (overlap Notify — attenzione doppie notifiche)
3. WhatsApp

## DoD (per modulo)

- [ ] Event/Route XotBase
- [ ] PHPStan 0 errori reali
- [ ] ≥1 Pest verde
- [ ] import-status aggiornato
- [ ] Commento Issue #14 con output comandi

## Non fare

- Disable a caso senza decisione D5-like.
- `@phpstan-ignore` di massa.
- Merge in Notify “per togliere errori”.
