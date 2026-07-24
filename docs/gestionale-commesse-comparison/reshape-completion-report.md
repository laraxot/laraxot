---
title: "Reshape completion report — moduli gestionale_commesse"
type: concept
tags: [gestionale-commesse, reshape, xotbase, completion-report]
created: 2026-07-24
updated: 2026-07-24
qmd: "reshape completion report XotBaseModel migrations providers gestionale_commesse functional readiness"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./models-user-refs-batch-fix.md"
  - "./functional-readiness.md"
  - "../../laravel/Modules/AiAssistant/docs/laraxot-reshape-fix.md"
  - "./laraxot-compliance-audit.md"
---

# Reshape completion report — 2026-07-24

Stato reshape modelli/provider. **Runtime aggiornato:** [functional-readiness.md](./functional-readiness.md).

## Aggiornamento sessione readiness (stesso giorno)

| Voce | Esito |
|---|---|
| Migrate path wave + Catalog + Billing | OK (tabelle core presenti) |
| FK users UUID | Mitigato (`string(36)`, no constrained) |
| AdminPanelProvider wave | OK — rotte `*/admin` vive |
| Document | Resta disabled (conflitto `media`) |
| Pest smoke | Aperto |

## Checklist per modulo (modelli == migrazioni, 0 riferimenti rotti)

| Modulo | Modelli | Migrazioni | `extends Model` residui | `App\Models\User` residui | Provider | Note |
|---|---:|---:|---:|---:|---|---|
| AiAssistant | 4 | 4 | 0 | 0 | ripristinato con binding | fix completo in sessione precedente |
| Billing | 7 | 7 | 0 | 0 | ripristinato (Gate InvoicePolicy) | |
| Bom | 2 | 2 | 0 | 0 | ripristinato (Gate Bom/BomItem) | |
| Catalog | 7 | 7 | 0 | 0 | ripristinato (5 Gate policy) | |
| Compliance | 9 | 9 | 0 | 0 | ripristinato (4 Gate policy) | migrazione monolitica 3→9 file splittati |
| EnergyBroker | 6 | 6 | 0 | 0 | ripristinato (6 Gate policy) | migrazione monolitica 1→5 file splittati |
| Fiscal | 0 | 0 | 0 | 0 | scaffold minimo, corretto | modulo solo Actions/Datas, nessun model per design (coerente con SRC `Domain/DTO+Actions+ValueObjects`) |
| Inventory | 5 | 5 | 0 | 0 | ripristinato (4 Gate policy) | |
| Production | 2 | 2 | 0 | 0 | ripristinato (2 Gate policy) | |
| PublicProcurement | 15 | 15 | 0 | 0 | ripristinato (15 Gate policy) | gap più grande: 1 migrazione monolitica → 15 file |
| TimberBilling | 4 | 4 | 0 | 0 | scaffold minimo, corretto (solo mergeConfigFrom, già gestito da XotBaseServiceProvider) | migrazione 2-tabelle → 2 file, timestamp riordinati per FK |
| Customer | 5 | 5 | 0 | 0 | già corretto (altro agente) | |
| WorkOrder | 5 | 5 | 0 | 0 | ripristinato (Gate WorkOrderPolicy) | |
| Intervention | 8 | 8 | 0 | 0 | ripristinato (bindings GoogleCalendar/Payment, Gate, Event listener, 2 Observer) | era rimasto lockato da altro agente nel primo giro |
| Signature | 2 | 2 | 0 | 0 | scaffold minimo, corretto (solo migrations/views, già gestito) | |
| Quotation | 5 | 5 | 0 | 0 | ripristinato (Gate Quotation/QuotationTemplate, comando `BackfillConfirmedAtCommand`) | lock stale rilasciato (PID del processo che lo deteneva non più attivo) e completato |

## Metodo applicato per ogni provider "sospetto" (16 righe)

1. Confrontato con `Providers/*ServiceProvider.php` del sorgente in `/var/www/_bases/gestionale_commesse/Modules/<Nome>/`.
2. Se il SRC aveva solo `loadMigrationsFrom`/`loadViewsFrom` (già coperti automaticamente da `XotBaseServiceProvider::boot()`), lo scaffold minimo è stato **lasciato invariato** (Fiscal, TimberBilling, Signature).
3. Se il SRC aveva `Gate::policy(...)`, binding DI, `Event::listen`, `Model::observe`, comandi console, questi sono stati **ripristinati** in un `register()`/`boot()` che chiama sempre `parent::register()`/`parent::boot()` prima di aggiungere la logica custom, con namespace aggiornati alla struttura reshapata (`Modules\X\Models\...` invece di `Modules\X\Infrastructure\Models\...`, `Modules\X\Policies\...` invece di `Infrastructure\Policies`).

## Migrazioni: pattern di split

Ogni migrazione monolitica (`Schema::create` multipli in un solo file `extends Migration`) è stata riscritta come **N file separati**, uno per tabella/modello, ciascuno:

```php
return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            // colonne, senza $table->timestamps()/softDeletes() manuali
            $this->updateTimestamps($table, hasSoftDeletes: true); // o false se il SRC non aveva softDeletes
        });
    }
};
```

Il nome del file (`create_{tabella}_table.php`, tabella al plurale) deve produrre, tramite `Str::singular()->studly()`, esattamente il nome della classe Model già esistente in `app/Models/` — verificato per tutti i file creati in questa sessione (`PublicProcurement`, `Compliance`, `EnergyBroker`, `TimberBilling`).

**Ordine dei timestamp**: dove una tabella dipende (FK `constrained()`) da un'altra dello stesso modulo, il file con la tabella dipendente ha timestamp successivo (vedi `TimberBilling`: `invoices` → `invoice_lines` → `payment_installments`/`e_invoices`, tutti retrocessi a `2026_07_08` per restare prima delle tabelle già esistenti con quella data).

## Cosa NON è stato verificato in questa sessione (fuori scope dichiarato)

- **PHPStan** non eseguibile per il problema preesistente noto (tema `Two` mancante, blocca il bootstrap applicativo per qualunque modulo — non è stato introdotto da questo lavoro, già segnalato in `Customer/docs/gestionale-commesse-port.md`).
- **Filament Resources/Pages**: non verificato se estendono `XotBaseResource`/pattern XotBase ovunque — verificato solo puntualmente su `AiAssistant` (che estende `Filament\Pages\Page` puro, lasciato così per assenza di un pattern XotBase-Page consolidato nel repo).
- Test Pest non eseguiti (bootstrap applicativo bloccato, vedi sopra).

## Aggiornamento — Quotation completato

Il lock su `Quotation` (PID 1198477) risultava stale: il processo non era più in esecuzione (`ps -p` senza risultati) a fronte di un lock creato oltre un'ora prima. Rilasciato manualmente e completato lo stesso identico pattern degli altri moduli: `QuotationServiceProvider` ripristinato con `Gate::policy(Quotation::class, QuotationPolicy::class)`, `Gate::policy(QuotationTemplate::class, QuotationTemplatePolicy::class)` e registrazione del comando `BackfillConfirmedAtCommand` (tutti verificati esistenti nel modulo reshapato). **Tutti e 16 i moduli sono ora completi**, nessun gap noto residuo.

## Aggiornamento 2026-07-24 — verifica approfondita (PHPStan + bug reale trovato)

### Bug reale corretto: `Platform` puntava a namespace inesistenti

`laravel/Modules/Platform/app/Providers/PlatformServiceProvider.php` importava ancora:

```php
use Modules\Billing\Infrastructure\Models\Invoice;
use Modules\Customer\Infrastructure\Models\Customer;
use Modules\Intervention\Infrastructure\Models\Intervention;
use Modules\Quotation\Infrastructure\Models\Quotation;
use Modules\WorkOrder\Infrastructure\Models\WorkOrder;
```

Namespace `Infrastructure\Models` che **non esiste più** da quando questi moduli sono stati reshapati sotto `app/Models`. Il commento nel file diceva esplicitamente "quei moduli non sono stati ancora reshape-ati in questa wave" — affermazione ormai falsa. Autoload avrebbe fallito al primo `Module::enable('Platform')`. Corretto a `Modules\{Nome}\Models\*`, rimosso il commento obsoleto. Verificato con grep ricorsivo su tutto il repo che non esistono altri riferimenti a `Infrastructure\`/`Application\`/`Domain\` di moduli già reshapati (namespace SRC plurale già verificato assente in precedenza).

### PHPStan L10 eseguito su tutti i 16 moduli

| Modulo | Errori propri (app/database) |
|---|---:|
| AiAssistant | 0 (18 errori, tutti in `phpstan-stubs/NeuronAI/*` — stub di terze parti preesistenti) |
| WorkOrder, Customer, Intervention, Quotation, PublicProcurement, EnergyBroker, Billing, Bom, Catalog, Inventory, Production, TimberBilling, Signature, Vehicle | **0** |
| **Compliance** | **17** — vedi sotto |

`Compliance`: 17 errori reali in `app/Filament/Resources/**`, `app/Actions/GeneratePosDraftForWorkOrderAction.php`, `app/Actions/GenerateRceePdfAction.php`, `database/seeders/ComplianceDemoSeeder.php`. Verificato con `git log -1`/`git status` che questi file **non sono stati toccati in nessuna sessione di questo reshape** (l'unico file di questo gruppo modificato da me è `ComplianceDemoSeeder.php`, solo per il fix `App\Models\User` → `Modules\User\Models\User`, riga diversa da quelle con errore). È debito tecnico preesistente del porting originale, non una regressione introdotta qui. Dettaglio errori: `json_encode` non-safe (`theCodingMachineSafe.function`), cast di `mixed` a stringa in Blade/encapsed string, parametri con tipo troppo permissivo (`int|string|null` vs `int|null`), costanti Filament dichiarate e mai usate. **Story separata da aprire** per il cleanup — fuori scope del reshape struttura moduli.
