---
title: "Porting gestionale_commesse — TimberBilling"
type: concept
tags: [import, gestionale-commesse, timberbilling, reshape]
created: 2026-07-23
updated: 2026-07-23
qmd: "porting gestionale_commesse TimberBilling reshape XotBase"
related:
  - "./import-status.md"
  - "./README.md"
---

# Porting gestionale_commesse — TimberBilling

## Contesto

Il modulo era stato riportato a una copia grezza DDD (incl. `app/Domain`,
`app/Application`, `app/Support`) da un processo concorrente che ha annullato un
precedente reshape XotBase. Questa sessione ha rifatto il reshape e corretto diversi
riferimenti non portati dalla vecchia app esterna:

- Modelli in `app/Models` che estendono `XotBaseModel` (già presenti, verificati).
- Provider `TimberBillingServiceProvider` estende `XotBaseServiceProvider`;
  `EventServiceProvider`/`RouteServiceProvider` XotBase-derivati con stub route.
- Filament Resources in `app/Filament/Resources` estendono `XotBaseResource`.
- Tutte le Actions in `app/Actions` ora usano il trait
  `Spatie\QueueableAction\QueueableAction` (mancava in tutti i file).
- Aggiunte cartelle `lang/it` e `lang/en` con `module.php` minimale (mancavano).
- Aggiunto test di fumo Pest in `tests/Unit/TimberBillingModuleSmokeTest.php`
  (i placeholder `tests/Feature/.gitkeep` e `tests/Unit/.gitkeep` erano vuoti).

### Bug corretti (riferimenti pendenti dalla vecchia app esterna)

- `App\Timber\Models\TimberProduct` → sostituito con `Modules\Catalog\Models\Product`
  (relazione `product()` in `TimberInvoiceLine`, select in `TimberInvoiceResource`).
- `App\Timber\Models\TimberCustomer` → sostituito con `Modules\Customer\Models\Customer`
  (relazioni `customer()` in `TimberInvoice`/`TimberPaymentInstallment`,
  `GetTimberBusinessAnalyticsQuery`).
- `Modules\Fiscal\Domain\DTO\*` e `Modules\Fiscal\Domain\Actions\*` → il modulo Fiscal
  è stato a sua volta già reshape-ato: i namespace corretti sono
  `Modules\Fiscal\Datas\*` e `Modules\Fiscal\Actions\*`. Aggiornati tutti i riferimenti
  in `TimberInvoiceToFiscalDataMapper`, `GenerateFatturaElettronicaXmlAction`,
  `GenerateTimberInvoicePaymentScheduleAction`, `GenerateTimberInvoicePdfAction`,
  `RecalculateTimberInvoiceTotalsAction`, `GetTimberVatLiquidationQuery`,
  `TimberVatRegisterPage`.
- `TimberInvoiceToFiscalDataMapper::buyer()` leggeva `$customer->business_name` e
  `$customer->sdi_recipient_code`, campi inesistenti sul modello `Customer`
  (che ha `legal_name`, non ha SDI recipient code) → corretto a `legal_name`,
  `sdiRecipientCode` impostato a `null`.
- Migrazioni: `customer_id` puntava a una tabella `timber_customers` mai creata
  (residuo dell'app esterna) → corretto a `customers` (tabella reale del modulo
  Customer). `product_id` puntava a `timber_products` mai creata → corretto a
  `products` (modulo Catalog). `source_document_id` puntava a `timber_documents`
  mai creata → reso `unsignedBigInteger` non vincolato (FK rimossa, dato che non
  esiste un modello/tabella "documenti" equivalente in questa base).
- Rimossa la relazione `TimberInvoice::sourceDocument()` (puntava alla classe
  inesistente `App\Timber\Models\TimberDocument`, non utilizzata altrove nel modulo).
- Aggiunte annotazioni `@property` su `TimberInvoice`, `TimberInvoiceLine`,
  `TimberPaymentInstallment` per allineare PHPStan alle colonne reali di migrazione.
- `WhatsAppMessageLink`: uso di `preg_replace` sostituito con
  `Safe\preg_replace` (regola `theCodingMachineSafe.function`).

### Gap noto e non risolto (fuori scopo "reasonable effort")

`app/Application/Queries/GetTimberAgendaQuery.php`,
`GetTimberBusinessAnalyticsQuery.php` e `GetTimberVatLiquidationQuery.php` usano
ancora `App\Timber\Models\TimberDocument`, un modello/tabella "documenti
commerciali" (ordini/DDT) della vecchia app esterna mai portato in questa base
(nessuna migrazione, nessun modello equivalente esistente). Portarlo
correttamente richiede una decisione di prodotto su cosa rappresenti
"documento" in questo gestionale (assente nello scope di questa sessione).
Queste 3 query restano quindi non funzionanti/dormant fino a decisione futura.

## PHPStan

Eseguito `./vendor/bin/phpstan analyse Modules/TimberBilling ... --memory-limit=-1`.
Errore fatale iniziale (blocco totale) causato da mismatch di namespace/proprietà
sopra descritti: risolto. Errori residui concentrati in
`app/Application/Queries` (il gap TimberDocument sopra), in alcune Filament
Resources/Pages, e in `config/config.php` (uso di `env()` fuori dalla root
`config/` di Laravel, avviso generico larastan comune a molti moduli
Nwidart di questo repo). Non rincorsi oltre la correzione degli errori semplici.

## Fix collaterale repo-wide (bloccante)

Durante il primo tentativo di PHPStan, un errore fatale PHP bloccava **qualsiasi**
analisi/test dell'intero repo:
`Type of ...\EventServiceProvider::$shouldDiscoverEvents must not be defined`
(proprietà tipizzata `bool` invece che non tipizzata, come nel parent Laravel).
Colpiva 11 moduli (`HR`, `Customer`, `Quotation`, `Platform`, `WhatsApp`,
`Document`, `Email`, `WorkOrder`, `Intervention`, `Signature`, `Vehicle`).
Corretto (rimosso il tipo `bool`) negli 8 moduli liberi (lock check→lock→fix→unlock
per ciascuno); `Intervention`, `Signature`, `Vehicle` erano lockati da altri agenti
e non sono stati toccati.

## Stato modulo

`modules_statuses.json`: `false` (disabilitato) — confermato dormiente per questa sessione.

## Rilevanza per la base clienti

TimberBilling (fatturazione per un'attività di commercio legname/quintali, fattura
elettronica, piani di pagamento) è una verticale molto specifica di settore.
Nessun segnale di domanda specifica nella base clienti attuale del gestionale
commesse. Suggerito restare dormiente fino a una richiesta esplicita da un cliente
del settore legname/commercio all'ingrosso con necessità di fatturazione elettronica
dedicata.

## Test migrati dal sorgente — nota 2026-07-24 (sessione parallela)

Sorgente: `gestionale_commesse/tests/Feature/TimberBilling/` (14 file). Al momento di
questa sessione di migrazione multi-modulo, `Modules/TimberBilling/tests/` risultava
**già esteso a 34 file** (`tests/Feature/*.php` + `tests/Unit/TimberBillingModuleSmokeTest.php`)
da una sessione di lavoro concorrente in corso (vedi `git log` — commit "migrate tests
from gestionale_commesse", debito D16 in `laravel/docs/gestionale-technical-debt.md`,
"migrazione in corso 2026-07-24"), con modifiche non ancora committate su `app/Actions`,
`app/Models/TimberDocument.php`, migrazioni e più file di `tests/Feature/`.

Per non entrare in conflitto con quel lavoro attivo, questa sessione **non ha toccato**
i file di test di TimberBilling. Verificato con
`vendor/bin/pest Modules/TimberBilling/tests --no-coverage`: 70 passed / 120 failed
(sull'insieme completo dei 5 moduli di questa sessione; il grosso dei failed è
TimberBilling). I fallimenti osservati non sono legati al gap infrastrutturale xot/sqlite
degli altri moduli dormienti, ma a business logic in corso di allineamento
(es. `TimberWoodSpeciesTest` su seeding specie legnose, `RegisterTimberPurchaseAction`
su validazione specie) — coerente con un porting ancora in corso nella sessione parallela.
