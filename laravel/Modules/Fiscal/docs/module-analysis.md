---
title: Fiscal Module Analysis
type: concept
tags: [fiscal, invoice, taxes, e-invoice]
created: 2026-09-05
updated: 2026-09-05
qmd: "fiscal module-analysis scopo religione filosofia politica zen"
module: Fiscal
---

# Fiscal Module Analysis

## Scopo
Fatturazione elettronica e compliance fiscale italiana. Generazione, invio, ricezione e conservazione sostitutiva di fatture elettroniche secondo formato SDI.

## Religione
- **SDI-first**: il Sistema Di Interscambio è il centro dell'universo fiscale
- **Formato FatturaPA**: XML conforme a specifiche Agenzia Entrate
- **Firma digitale CAdES**: firma attached su file XML
- **Conservazione 10 anni**: storage compliance per termini di legge
- **Codice TD/TD_BF**: tipologie documento corrette per ognicaso
- **Split payment ready**: gestione IVA per cassa

## Filosofia
- **Compliance by design**: il modulo è progettato per rispettare la legge, non per aggirarla
- **Automatismo dove possibile**: invio automatico dopo emissione
- **Traceability totale**: ogni operazione fiscale è loggata
- **Separazione responsabilità**: la fattura è del commercialista, il modulo è lo strumento

## Politica
- Ogni fattura ha: numero progressivo (anno+progressivo), data, cedente/prestatore, cessionario/committente, righe, totali, IVA
- Il formato XML è validato prima dell'invio a SDI
- Le fatture passive sono importate da SDI con parsing automatico
- La conservazione sostitutiva richiede firma digitale e marca temporale
- Gli XML sono stored immutabili post-invio
- Le note credito sono collegate alla fattura originaria

## Perché
Perché la fatturazione elettronica è obbligatoria in Italia e richiede infrastruttura specifica che nessun modulo generico ha: formato SDI, firmacrittura, invio/ricezione SDI, conservazione.

## Zen
Chi ha emesso, chi ha ricevuto, chi ha conservato - la fattura viaggia, il dato resta.

## Cosa manca
- Integrazione registri IVA automatici
- Liquidazione IVA periodica
- Spesometro / comunicazione liquidazioni
- Reverse charge automatico
- Integrazione con software contabilità (Sage, Zucchetti, etc.)
- Supporto esterometro

## Cosa aggiungerei
- AI per detection anomalie fatture
- Automatic VAT number validation (VIES)
- Cash flow forecasting basato su scadenze fatture
- Multi-company fiscal code management
- Tax calendar con reminder scadenze
- Fattura differita automaticaper contratti ricorrenti

## Divisione o Unione
- **Mantieni separato**: fiscalità è dominio altamente specialistico
- **Potenziale unione**: potrebbe assorbire Billing se focalizzato solo su fiscalità
- **Conflitto**: con Billing (la fattura è sia invoice che fiscal document), con Compliance per aspetti regolatori