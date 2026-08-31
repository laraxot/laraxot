# Wts

Gestione del ciclo di trattamento rifiuti dell'impianto WTS: dall'ingresso del
materiale dichiarato dall'ERP legacy (ECOS) fino agli output finiti, passando per
lavorazioni, etichettatura e audit trail.

Il modulo ha un pannello Filament dedicato (`/wts`): chi lavora in impianto ci sta
dentro tutto il turno e non deve vedere il resto del gestionale.

## Dominio

| Concetto | Modello | Nota |
|---|---|---|
| Viaggio in ingresso da ERP | `WtsInboundRecord` | porta con se' il payload ECOS grezzo |
| Partita presa in carico | `WtsInboundLot` | nasce dalla presa in carico di un record |
| Unita' fisica (collo) | `WtsUnit` | generata a serie, etichettata singolarmente |
| Lavorazione | `WtsProcessingStep` | consuma unita', produce materiali derivati |
| Output | `WtsDerivedMaterial` | il prodotto della lavorazione |
| Scheda tecnica | `WtsTechnicalSheetSnapshot` | fotografia della scheda al momento dell'ingresso |
| Etichette e stampa | `WtsLabelPrint`, `WtsPrintJob` | la coda viene svuotata dall'agente locale |
| Parametri operativi | `WtsOperationalParameter` | pesi standard e soglie, modificabili a runtime |
| Non conformita' | `WtsNonConformityReport` | |
| Audit | `WtsAuditLog` | traccia le azioni sensibili |

## Integrazioni

- **ECOS (ERP legacy)**: lettura via ODBC (`app/Ecos/EcosOdbcConnection`). La scheda
  tecnica arriva da un gateway dietro contratto (`Legacy\LegacyTechnicalSheetGateway`),
  cosi' il dominio non conosce il gestionale a monte e i test possono sostituirlo.
- **Stampa etichette ZPL**: `app/Label/ZplBuilder` genera lo ZPL; i job restano in coda
  e l'agente sul PC di impianto li preleva via API.
- **QR**: `bacon/bacon-qr-code` per l'SVG inline, `^BQ` nativo sulle etichette ZPL.

## Pannello

`Providers/Filament/AdminPanelProvider` monta le pagine a mano: si dichiarano tutte
`$isDiscovered = false`, quindi senza l'elenco esplicito il pannello esporrebbe solo
il login e ogni rotta risponderebbe 404.

## Documentazione collegata

- [import-status.md](import-status.md) — cosa e' stato portato e cosa e' cambiato
- [../../../../docs/gestionale-commesse-comparison/delta-second-tranche.md](../../../../docs/gestionale-commesse-comparison/delta-second-tranche.md)
