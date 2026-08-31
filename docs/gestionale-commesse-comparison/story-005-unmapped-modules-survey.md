---
title: "STORY-005 — Survey moduli senza mapping evidente (gestionale_commesse → base_workorder_fila5)"
type: story
created: 2026-07-23
---

# STORY-005 — Survey dei 19 moduli non ancora mappati

Analisi rapida dei moduli di `_bases/gestionale_commesse/Modules/` senza equivalente ovvio in
`base_workorder_fila5/laravel/Modules/`. Tutti i moduli sorgente seguono struttura DDD
(`Application/Domain/Infrastructure/Filament`), non `app/Models` classico Laraxot: i modelli
Eloquent vivono in `Infrastructure/Models/`.

## Billing

**Scopo**: fatturazione attiva/passiva legata alle commesse/work order: fatture cliente, fatture
fornitore, DDT, e collegamento fattura↔work order.

**Modelli/feature chiave**:
- `Infrastructure/Models/Invoice.php`, `SupplierInvoice.php`, `SupplierInvoiceLine.php`
- `Infrastructure/Models/DeliveryNote.php`, `DeliveryNoteLine.php` (DDT)
- `Infrastructure/Models/InvoiceWorkOrderLink.php`, `Supplier.php`
- Risorse Filament: `Filament/Resources/InvoiceResource.php`, `SupplierInvoiceResource.php`, `DeliveryNoteResource.php`

**Overlap in base_workorder_fila5**: nessuna sovrapposizione (nessun modulo di fatturazione/anagrafica fornitori esistente; Xot/User non coprono fatturazione).

**Proposta naming**: `Billing` (nome invariato, coerente col dominio).

## Bom

**Scopo**: gestione distinta base (Bill of Materials) per prodotti/lavorazioni, con righe componenti.

**Modelli/feature chiave**:
- `Infrastructure/Models/Bom.php`, `BomItem.php`
- `Filament/Resources/BomResource.php`
- `module.json`: "Bills of materials (distinta base)"

**Overlap in base_workorder_fila5**: nessuna sovrapposizione.

**Proposta naming**: `Bom` (acronimo consolidato nel dominio industriale, mantenere).

## Catalog

**Scopo**: catalogo prodotti/servizi con modelli, tipi parte, unità di misura, frequenze di
manutenzione e import da cataloghi esterni (Metel — distributore materiale elettrico).

**Modelli/feature chiave**:
- `Infrastructure/Models/Product.php`, `CatalogModel.php`, `CatalogPartType.php`, `CatalogMeasureUnit.php`, `CatalogFrequency.php`
- `Infrastructure/Models/MetelCatalog.php`, `MetelCatalogItem.php` (import cataloghi fornitore Metel)
- `Filament/Resources/ProductResource.php`, `CatalogModelResource.php`

**Overlap in base_workorder_fila5**: nessuna sovrapposizione diretta (Media/Cms non gestiscono cataloghi prodotto).

**Proposta naming**: `Catalog` (invariato).

## Compliance

**Scopo**: conformità normativa lato impianti/interventi tecnici — impianti termici, caldaie,
controlli di manutenzione periodica, documenti di conformità, subappaltatori e ordini di
modifica lavori. Diverso dal GDPR (privacy) del target: qui è compliance tecnico-impiantistica.

**Modelli/feature chiave**:
- `Infrastructure/Models/HeatingSystem.php`, `Boiler.php`, `Equipment.php`, `CustomerSystem.php`
- `Infrastructure/Models/MaintenanceControl.php`, `ComplianceDocument.php`, `WorkOrderReadinessItem.php`
- `Infrastructure/Models/Subcontractor.php`, `WorkOrderChangeOrder.php`
- `module.json`: "Artisan and work-order compliance layer"

**Overlap in base_workorder_fila5**: parziale/nominale con `Gdpr` solo per il concetto generico di "compliance", ma il dominio è opposto (tecnico-impiantistico vs privacy/consensi). Nessuna vera sovrapposizione funzionale.

**Proposta naming**: `Compliance` invariato, ma valutare rinominare in `PlantCompliance` o `TechnicalCompliance` per evitare ambiguità col termine GDPR-style "Compliance" già suggerito altrove.

## Customers

**Scopo**: anagrafica clienti, siti/sedi cliente, contatti, ruoli contatto e rivenditori (reseller).
Anagrafica commerciale, distinta dagli utenti applicativi (User/Auth).

**Modelli/feature chiave**:
- `Infrastructure/Models/Customer.php`, `CustomerSite.php`, `Contact.php`, `ContactRole.php`, `Reseller.php`
- `Filament/Resources/CustomerResource.php`, `ResellerResource.php`

**Overlap in base_workorder_fila5**: nessuna sovrapposizione (User gestisce utenti autenticati, non anagrafica clienti/CRM — verificato che User è analizzato da altro agente e comunque non ha "Customer" model).

**Proposta naming**: `Customers` (invariato) oppure `CRM` se si vuole allineare a nomenclatura più generica.

## Documents

**Scopo**: modulo molto sottile, wrapper di gestione documentale generico legato ai work order
(un solo modello `Media`, probabilmente un alias/estensione locale di Spatie Media Library).

**Modelli/feature chiave**:
- `Infrastructure/Models/Media.php` (unico modello)
- Nessun README, `module.json` senza descrizione

**Overlap in base_workorder_fila5**: sovrapposizione forte con `Media` (già presente, basato su Spatie Media Library — vedi `Modules/Media/app/Models/Media.php`, `MediaConvert.php`, `TemporaryUpload.php`). Il modulo Documents sorgente sembra ridondante rispetto a un Media module maturo.

**Proposta naming**: nessun nuovo modulo necessario — mappare le funzionalità su `Media` esistente.

## Email

**Scopo**: integrazione email in ingresso con elaborazione assistita da AI per creare/aggiornare
work order automaticamente da email ricevute (parsing, thread, identità mittente).

**Modelli/feature chiave**:
- `Infrastructure/Models/EmailMessage.php`, `EmailConversation.php`, `EmailContactIdentity.php`
- `module.json`: "Inbound email integration with AI-assisted work order processing"
- Cartelle `Console/`, `Http/`, `Routes/` per webhook/comandi ingest

**Overlap in base_workorder_fila5**: parziale con `Notify` (che gestisce `MailTemplate`, `NotificationLog`, canali di notifica) ma Notify è orientato a invio/outbound notifiche, non a ricezione/parsing email in ingresso con AI. Nessuna vera sovrapposizione sul lato inbound+AI.

**Proposta naming**: se costruito, `EmailInbox` o `Email` (mantenendo il nome ma chiarendo in descrizione che è complementare, non sostitutivo, di Notify).

## EnergyBroker

**Scopo**: verticale di business per intermediazione energetica — intake documenti (bollette,
POD), OCR, matching clienti, storico forniture, upload massivi.

**Modelli/feature chiave**:
- `Infrastructure/Models/EnergyBrokerDocument.php`, `EnergyBrokerSupplyPoint.php`, `EnergyBrokerSupplyHistory.php`
- `Infrastructure/Models/EnergyBrokerCustomerMatch.php`, `EnergyBrokerImportBatch.php`, `EnergyBrokerLargeUpload.php`
- `module.json`: "Energy broker document intake, OCR and supply history workflows"

**Overlap in base_workorder_fila5**: nessuna sovrapposizione — dominio verticale molto specifico assente dal target.

**Proposta naming**: `EnergyBroker` (invariato, dominio auto-esplicativo).

## Fiscal

**Scopo**: motore fiscale trasversale/condiviso (vertical-agnostic) — totali IVA, liquidazione IVA,
piani di pagamento, XML fattura elettronica FatturaPA. Usato sia da Billing sia da TimberBilling.

**Modelli/feature chiave**:
- Solo cartella `Domain/` (nessun `Infrastructure/Models` popolato — probabilmente logica pura/value objects)
- `module.json`: "Shared, vertical-agnostic fiscal engine: VAT totals, VAT liquidation, payment plans and FatturaPA e-invoice XML"
- `priority: 5` (caricato presto, dipendenza di altri moduli)

**Overlap in base_workorder_fila5**: nessuna sovrapposizione.

**Proposta naming**: `Fiscal` (invariato) — nome coerente con dominio IT fiscale/e-fattura, da non confondere con "Invoicing" che è più Billing.

## Interventions

**Scopo**: gestione interventi tecnici sul campo — note giornaliere, righe materiali usati,
segnalazioni problemi, timbrature (time punch), condivisione report intervento, integrazione
Google Calendar per tecnici.

**Modelli/feature chiave**:
- `Infrastructure/Models/Intervention.php`, `DailyNote.php`, `InterventionMaterialLine.php`
- `Infrastructure/Models/IssueReport.php`, `TimePunch.php`, `InterventionReportShare.php`
- `Infrastructure/Models/UserGoogleCalendarSetting.php`

**Overlap in base_workorder_fila5**: probabile sovrapposizione concettuale con `WorkOrders` (analizzato da altro agente) essendo gli interventi la componente esecutiva/operativa dei work order; da verificare con quell'analisi. Nessuna sovrapposizione con i moduli qui listati (Employee escluso da questa survey).

**Proposta naming**: `Interventions` (invariato) — da coordinare col mapping WorkOrders per evitare duplicazione.

## Inventory

**Scopo**: gestione magazzino — depositi, ubicazioni, movimenti di magazzino, tracciamento unità/seriali prodotto.

**Modelli/feature chiave**:
- `Infrastructure/Models/Warehouse.php`, `WarehouseLocation.php`, `InventoryMovement.php`
- `Infrastructure/Models/ProductUnit.php`, `InventoryOverviewRow.php`
- `module.json`: "Warehouses, stock, movements, and optional unit/serial tracking"

**Overlap in base_workorder_fila5**: nessuna sovrapposizione.

**Proposta naming**: `Inventory` (invariato).

## Platform

**Scopo**: modulo tecnico-infrastrutturale trasversale — impostazioni piattaforma e audit log di sistema.

**Modelli/feature chiave**:
- `Infrastructure/Models/PlatformSetting.php`, `AuditLog.php`
- `module.json`: "Technical platform module (infrastructure)"

**Overlap in base_workorder_fila5**: parziale con `Xot` (modulo base/infrastrutturale del framework Laraxot) per il concetto di settings/audit trasversali, ma Xot è il core framework XotBase mentre Platform qui è più applicativo (settings di business + audit). Sovrapposizione debole, da verificare in dettaglio se Xot già espone AuditLog/Settings generici.

**Proposta naming**: se serve, `PlatformSettings` per distinguerlo chiaramente da Xot core.

## Production

**Scopo**: gestione ordini di produzione e relative fasi (workflow produttivo, es. per articoli custom/lavorazioni).

**Modelli/feature chiave**:
- `Infrastructure/Models/ProductionOrder.php`, `ProductionPhase.php`
- `Filament/Resources/ProductionOrderResource.php`
- `module.json`: "Production orders and phases"

**Overlap in base_workorder_fila5**: nessuna sovrapposizione.

**Proposta naming**: `Production` (invariato).

## PublicProcurement

**Scopo**: gestione bandi e appalti pubblici legati ai work order — opportunità di gara, contratti,
requisiti documentali, garanzie, stati avanzamento lavori (SAL), certificati di pagamento, estrazioni AI da bandi.

**Modelli/feature chiave**:
- `Infrastructure/Models/PublicProcurementTenderOpportunity.php`, `PublicProcurementContract.php`
- `Infrastructure/Models/PublicProcurementProgressReport.php`, `PublicProcurementPaymentCertificate.php`
- `Infrastructure/Models/PublicProcurementAiExtraction.php`, `PublicProcurementGuarantee.php`, `PublicProcurementDeadline.php`
- `module.json`: "Public tender and procurement management for work orders"

**Overlap in base_workorder_fila5**: nessuna sovrapposizione — dominio verticale (PA/appalti) assente dal target.

**Proposta naming**: `PublicProcurement` (invariato, terminologia standard IT/EU per appalti pubblici).

## Quotations

**Scopo**: preventivazione — preventivi, righe preventivo, template di preventivo riutilizzabili, sopralluoghi tecnici (site survey) propedeutici al preventivo.

**Modelli/feature chiave**:
- `Infrastructure/Models/Quotation.php`, `QuotationItem.php`
- `Infrastructure/Models/QuotationTemplate.php`, `QuotationTemplateItem.php`
- `Infrastructure/Models/SiteSurvey.php`
- `Filament/Resources/QuotationResource.php`, `SiteSurveyResource.php`

**Overlap in base_workorder_fila5**: nessuna sovrapposizione.

**Proposta naming**: `Quotations` (invariato).

## Signatures

**Scopo**: modulo minimale per firme digitali/grafiche e schizzi (sketch) — probabilmente firma cliente su rapportino/intervento.

**Modelli/feature chiave**:
- `Infrastructure/Models/Signature.php`, `Sketch.php`
- Nessun README, nessuna descrizione in `module.json`

**Overlap in base_workorder_fila5**: nessuna sovrapposizione diretta con Gdpr (che gestisce consensi, non firme grafiche/e-signature). Sovrapposizione debole/nominale solo per il tema "conformità legale", non funzionale.

**Proposta naming**: `Signatures` (invariato).

## TimberBilling

**Scopo**: fatturazione verticale specifica per il settore legno/forestale (timber), con fatture,
righe fattura, fattura elettronica e piani di rateizzazione pagamento dedicati. Ha persino
`package.json`/`vite.config.js` propri (asset frontend dedicati).

**Modelli/feature chiave**:
- `Infrastructure/Models/TimberInvoice.php`, `TimberInvoiceLine.php`
- `Infrastructure/Models/TimberEInvoice.php`, `TimberPaymentInstallment.php`
- Dipende da `Fiscal` (motore fiscale condiviso, vedi sopra)

**Overlap in base_workorder_fila5**: nessuna sovrapposizione (verticale di nicchia, probabilmente da NON portare nel target se il dominio di business non è timber/legno — da confermare con lo stakeholder).

**Proposta naming**: `TimberBilling` (invariato) — o scartare se il verticale legno non è pertinente al progetto target.

## Vehicles

**Scopo**: anagrafica veicoli aziendali/flotta e relativa documentazione (es. libretto, assicurazione, revisione).

**Modelli/feature chiave**:
- `Infrastructure/Models/Vehicle.php`, `VehicleDocument.php`
- `Filament/Resources/VehicleResource.php`

**Overlap in base_workorder_fila5**: nessuna sovrapposizione.

**Proposta naming**: `Vehicles` (invariato).

## WhatsApp

**Scopo**: integrazione con WhatsApp Business Cloud API (Meta) per gestione conversazioni con
elaborazione assistita da AI, analoga a Email ma su canale WhatsApp, per creazione/aggiornamento work order da messaggi.

**Modelli/feature chiave**:
- `Infrastructure/Models/WhatsAppConversation.php`, `WhatsAppMessage.php`, `WhatsAppContactIdentity.php`
- `module.json`: "WhatsApp Business Cloud API integration with AI-assisted work order processing"

**Overlap in base_workorder_fila5**: parziale con `Notify` solo per il concetto di canale di messaggistica/notifica outbound, ma WhatsApp qui è bidirezionale (ricezione + AI) come Email. Nessuna vera sovrapposizione funzionale con i moduli esistenti.

**Proposta naming**: `WhatsApp` (invariato) — da valutare in combinazione con `AI`/`AiAssistant` (analizzato da altro agente) per la parte di elaborazione AI condivisa con Email.

## Riepilogo sovrapposizioni

| Modulo sorgente | Overlap target | Tipo |
|---|---|---|
| Documents | Media | forte (stesso concetto, Media più maturo) |
| Compliance | Gdpr | debole/nominale (dominio diverso: tecnico vs privacy) |
| Platform | Xot | debole/nominale (settings/audit trasversali) |
| Email | Notify | debole (outbound vs inbound+AI) |
| WhatsApp | Notify | debole (outbound vs bidirezionale+AI) |
| Signatures | Gdpr | debole/nominale |
| Interventions | WorkOrders (altro agente) | da verificare in cross-analisi |
| Billing, Bom, Catalog, Customers, EnergyBroker, Fiscal, Inventory, Production, PublicProcurement, Quotations, TimberBilling, Vehicles | — | nessuna sovrapposizione, moduli candidati a creazione ex-novo |
