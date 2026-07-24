---
title: "Batch fix — XotBaseModel + Modules\\User\\Models\\User su tutti i moduli replicati"
type: concept
tags: [gestionale-commesse, xotbase, batch-fix, models, user-reference]
created: 2026-07-24
updated: 2026-07-24
qmd: "batch fix XotBaseModel App Models User replica modules laraxot"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "../../laravel/Modules/AiAssistant/docs/laraxot-reshape-fix.md"
  - "./laraxot-compliance-audit.md"
---

# Batch fix — modelli e riferimenti User su tutti i moduli replicati

> Stesso problema riscontrato e risolto singolarmente su `AiAssistant` (vedi [laraxot-reshape-fix.md](../../laravel/Modules/AiAssistant/docs/laraxot-reshape-fix.md)), esteso a tutti gli altri moduli replicati con `app/` già presente.

## Fix applicati (2026-07-24)

Su `Billing, Bom, Catalog, Compliance, EnergyBroker, Fiscal, Inventory, Production, PublicProcurement, TimberBilling, Customer, WorkOrder, Quotation, Signature`:

1. Ogni modello in `app/Models/*.php` con `extends Model` (Eloquent puro) → `extends XotBaseModel` (`Modules\Xot\Models\XotBaseModel`).
2. Ogni riferimento a `App\Models\User` (sia `use` sia FQCN inline `\App\Models\User::class`) → `Modules\User\Models\User`, in `app/Models`, `app/Providers`, `app/Filament/**`, `database/seeders/**`.
3. `php -l` verificato senza errori su tutti i file toccati in tutti i moduli.

`Intervention` **escluso da questo giro**: modulo lockato da un altro agente al momento del fix (`Intervention.lock`, PID attivo) — da rifare con lo stesso pattern quando si libera.

## Gap noti, NON risolti in questo giro (da fare come step successivo)

1. **Migrazioni ancora monolitiche o incomplete** per diversi moduli — conteggio migrazioni vs modelli non combacia:
   - `Bom`: 1 migrazione / 2 modelli
   - `Production`: 1 migrazione / 2 modelli
   - `Catalog`, `EnergyBroker`: 2 migrazioni / 7 e 6 modelli
   - `PublicProcurement`: **1 migrazione / 15 modelli** (il caso più grosso)
   - `Signature`: **0 migrazioni / 3 modelli**
   - `Fiscal`: 0 migrazioni / 0 modelli (modulo solo Actions/VO, coerente — nessun gap)

   Vanno splittate in 1 file per modello, `extends XotBaseMigration`, come già fatto per `AiAssistant` (vedi doc collegata).

2. **ServiceProvider ridotti a scaffold minimo** (16 righe, solo `name`/`module_dir`/`module_ns`) su tutti questi moduli, contro un `Providers/*ServiceProvider.php` SRC generalmente più corposo (es. `Billing/Providers/BillingServiceProvider.php` SRC = 35 righe). **Non verificato se contenevano binding/Gate/RateLimiter custom persi nel reshape**, come era successo (e poi risolto) per `AiAssistantServiceProvider`. Da controllare modulo per modulo confrontando con il `Providers/*.php` originale in `/var/www/_bases/gestionale_commesse/Modules/<Nome>/Providers/`.

3. `Intervention` non ancora passato per il fix modelli/User (lock altrui).

## Priorità consigliata per il prossimo giro

1. `PublicProcurement` (15 modelli, 1 migrazione — gap più grande)
2. `Signature` (0 migrazioni)
3. Verifica provider persi per `Billing`, `Customer`, `WorkOrder`, `Quotation` (moduli "core" Wave 1 secondo `roadmap.md`)
4. `Intervention` non appena sbloccato
