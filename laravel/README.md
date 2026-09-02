# base_workorder_fila5: la base Laraxot per il field service, dalla commessa alla fattura

<!-- laraxot:host-badges:start -->
<!-- laraxot:host-badges:end -->

> **Una sola installazione Laravel, decine di moduli indipendenti, zero controller nel frontoffice. Tutto ciò che serve per gestire clienti, commesse, interventi, preventivi, fatture, magazzino, produzione e conformità, con la disciplina Laraxot che rende ogni modulo riconoscibile al primo sguardo.**

## In trenta secondi

Questa cartella è l'applicazione Laravel del progetto. Il dominio è il field service: aziende che vendono interventi tecnici, li pianificano, li eseguono sul campo, li fatturano e devono dimostrare la conformità di ciò che hanno fatto. Il codice non vive in `app/` ma in `Modules/`: ogni modulo è un repository git separato, con i propri modelli, Actions, risorse Filament, migrazioni, test e documentazione. Il tema pubblico vive in `Themes/`. Il progetto host decide quali moduli accendere in `modules_statuses.json` e quale tema usare nella configurazione del tenant in `config/local/`.

## Perché esiste

Un gestionale monolitico invecchia con il primo cliente che chiede qualcosa di diverso. Questa base nasce dal porting di un gestionale field service esistente verso una struttura in cui ogni dominio è un modulo riusabile: la stessa anagrafica clienti serve una segheria e un installatore termico, lo stesso motore fiscale serve la fatturazione e il verticale legname, la stessa identità serve tutti i pannelli. Il valore non è nel singolo modulo ma nella regola che li rende componibili.

## Come funziona

- **Moduli nwidart.** Ogni cartella in `Modules/` ha `module.json`, `composer.json`, `app/`, `database/`, `lang/`, `resources/`, `tests/`, `docs/`. Il caricamento è governato da `modules_statuses.json`.
- **Fondamenta Xot.** Nessun modulo estende Filament, Livewire o Eloquent direttamente: estende `XotBaseModel`, `XotBaseResource`, `XotBasePage`, `XotBaseWidget`, `XotBaseServiceProvider`, `XotBasePanelProvider`. Un aggiornamento di framework si assorbe nel modulo `Xot`, non in tutti gli altri.
- **Backoffice Filament, più pannelli.** Il modulo `Xot` registra il pannello admin; il modulo `Platform` aggiunge i pannelli operatore, cliente e fornitore; `Cms` espone il pannello front. Ogni modulo contribuisce le proprie risorse al pannello giusto tramite il proprio `AdminPanelProvider`.
- **Frontoffice Folio e Volt.** Le pagine pubbliche sono file Blade nel tema, risolte da Laravel Folio e rese interattive da Livewire Volt. Il flusso è: richiesta, Folio, pagina Blade, componente Volt, Action, modello. Nessun controller, nessuna rotta scritta in `routes/web.php` per le pagine del tema.
- **Business logic in Actions.** Le operazioni sono classi Spatie Queueable Action con un solo metodo `execute()`: si testano da sole, si accodano quando pesanti, si compongono tra moduli senza classi `*Service`.
- **Multi tenant.** Il modulo `Tenant` risolve il tenant dal dominio e carica `config/local/<tenant>/`, dove il progetto host dichiara tema, moduli e impostazioni.

## Il ciclo del field service, modulo per modulo

| Fase | Moduli | Cosa succede |
|---|---|---|
| Anagrafica | `Customer`, `Geo` | Clienti, sedi, referenti, rivenditori; indirizzi normalizzati, distanze e mappe |
| Vendita | `Catalog`, `Costing`, `Quotation`, `MetalWork`, `Signage` | Prodotti e listini, centri di lavoro condivisi, preventivi da rilievi, da disegni CAD o da file grafici |
| Esecuzione | `WorkOrder`, `Intervention`, `Employee`, `Vehicle`, `Signature`, `Document` | Commessa con macchina a stati, interventi con timbrature in campo, presenze, mezzi, firme e foto |
| Chiusura | `Billing`, `Fiscal` | Fatture attive dalle commesse, DDT, fatture fornitore, IVA, piani di pagamento, XML FatturaPA |
| Materiali e produzione | `Inventory`, `Bom`, `Production` | Magazzini e movimenti, distinte base, ordini di produzione a fasi |
| Conformità e appalti | `Compliance`, `PublicProcurement`, `Wts` | Impianti termici e F-Gas, gare e contratti pubblici, tracciabilità rifiuti |
| Canali | `Email`, `WhatsApp`, `Notify`, `AI`, `AiAssistant` | Posta e messaggi in ingresso con identità risolta, notifiche multicanale, proposte AI con approvazione umana |
| Verticali | `Timber`, `EnergyBroker`, `Thermo`, `Dds`, `AsdSoci`, `Giveback` | Segheria completa, broker energetico, e quattro verticali demo senza persistenza propria |
| Piattaforma | `Xot`, `User`, `Tenant`, `Platform`, `Lang`, `UI`, `Media`, `Job`, `Activity`, `Gdpr`, `Cms`, `Seo`, `Blog`, `Comment`, `Rating` | Fondamenta, identità, tenant, pannelli, traduzioni, componenti, file, code, audit, privacy, contenuti |

## I moduli, misurati

<!-- laraxot:modules:start -->
<!-- laraxot:modules:end -->

## La visione

Un ecosistema in cui aggiungere un cliente significa aggiungere una cartella di configurazione, aggiungere un dominio significa aggiungere un modulo, e cambiare framework significa toccare un modulo solo. Il codice che scriviamo oggi deve reggere il cliente che non conosciamo ancora.

## Lo scopo

- Portare un intero gestionale field service dentro moduli Laraxot riusabili, uno per dominio.
- Tenere ogni modulo installabile da solo, con dipendenze dichiarate in `composer.json` e `module.json`.
- Offrire pannelli diversi a pubblici diversi (amministrazione, operatori, clienti, fornitori) sulla stessa base di codice.
- Rendere misurabile la qualità: analisi statica al livello massimo, test per modulo, documentazione per modulo.

## Politica

- **PHPStan al livello massimo su tutti i moduli**, con `phpstan.neon` intoccabile: niente `ignoreErrors`, niente baseline. Gli errori si correggono.
- **I dati sono sacri.** Mai `migrate:fresh`, `migrate:refresh` o `--force` su database esistenti. Lo schema lo decide il maintainer.
- **Una tabella, una migrazione**, con `XotBaseMigration`. Le colonne si aggiungono aggiornando la migrazione esistente del modulo, non con file duplicati.
- **Git va solo avanti.** Nessun ripristino di file da commit vecchi; i conflitti si risolvono, non si scavalcano.
- **Script fuori dalla root.** Tutto ciò che è operativo vive in `../bashscripts/tools/<area>/` ed è documentato in `../bashscripts/docs/`.
- **Ogni modulo è un repository.** Dopo una modifica: analisi statica, test, commit e push nel repository del modulo e in quello del progetto.

## Religione

- Mai estendere classi Filament direttamente: sempre `XotBase*`.
- Actions Spatie invece di classi `*Service`; mai Actions che fanno da proxy a relazioni Eloquent già presenti.
- Frontoffice con Folio e Volt, Filament solo nel backoffice.
- Etichette e testi dai file in `lang/`, mai `->label('testo')`.
- Mai `property_exists()` su un modello Eloquent.
- La cartella `docs/` di ogni modulo è la sua memoria: si legge prima e si aggiorna dopo.

## Filosofia

DRY e KISS portati alle conseguenze. Ogni concetto vive in un solo posto: il costo orario in `Costing`, l'IVA in `Fiscal`, l'identità in `User`, la geografia in `Geo`. Ogni regola ha un motivo scritto in un file di documentazione, e ogni errore si corregge invece di essere ignorato. La semplicità qui non è fare poco: è fare una cosa sola per volta, bene, e non rifarla mai.

## Zen

> Prima capire lo scopo, la logica, la religione, la politica e lo zen del codice. Poi scrivere.

## Quickstart

```bash
composer install
cp .env.example .env && php artisan key:generate
php artisan module:list                 # stato dei moduli da modules_statuses.json
composer dev                            # server, coda, log e Vite insieme
```

Qualità, per singolo modulo o per tutti:

```bash
./vendor/bin/phpstan analyse Modules --memory-limit=-1
./vendor/bin/phpstan analyse Modules/WorkOrder --memory-limit=-1
composer test                           # php artisan test, suite per modulo in phpunit.xml
./vendor/bin/pest Modules/WorkOrder/tests
```

Badge e tabella dei moduli di questo file e dei README dei moduli si ricalcolano con:

```bash
bash ../bashscripts/tools/readme/module-readme-badges.sh --host
bash ../bashscripts/tools/readme/module-readme-badges.sh --check   # esce 1 se un README è stantio o cita un progetto host
```

## Documentazione

| Cosa | Dove |
|---|---|
| Wiki tecnica dell'applicazione | [docs/](./docs/) |
| Wiki e memoria del progetto (second brain) | [../docs/wiki/](../docs/wiki/) |
| Story BMAD e stato sprint | [../docs/stories/](../docs/stories/), [../docs/sprint-status.yaml](../docs/sprint-status.yaml) |
| Fondamenta e credo Laraxot | [Modules/Xot/README.md](./Modules/Xot/README.md), [Modules/Xot/docs/](./Modules/Xot/docs/) |
| Documentazione di ogni modulo | `Modules/<Nome>/README.md` e `Modules/<Nome>/docs/` |
| Temi | [Themes/](./Themes/) |
| Script operativi e loro guida | [../bashscripts/docs/](../bashscripts/docs/) |

---

Applicazione Laravel del progetto `base_workorder_fila5`, famiglia **Laraxot**. Badge e tabella si rigenerano con `bash ../bashscripts/tools/readme/module-readme-badges.sh --host`; il testo si cura a mano.
