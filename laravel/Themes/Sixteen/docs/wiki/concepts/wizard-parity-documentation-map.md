---
title: "wizard parity documentation map — segnalazione theme sixteen"
type: concept
confidence: medium
created: 2026-05-21
updated: 2026-05-21
tags: [segnalazione, wizard, parity, documentation-dry, sixteeen]
related:
  - segnalazione-02-dati-design-comuni-vs-local.md
  - ../../../../Modules/docs/redundancy-report.md
sources: []
---

# Mappa documentazione parity wizard Segnalazione (Sixteen)

## Scopo

Esistono **molte pagine `segnalazione-*`** sotto [`concepts/`](.) perché parity Design Comuni vs implementazione locale è stata spezzettata per **step**, **tipo di asset** e **tipo di regressione**. Non è “codice ridondante”, ma rischio **sovraccarico cognitivo** se non si sa quale leggere per prima.

Collegamento tecnico modulo/codice ripetuto: [`Modules/docs/redundancy-report.md`](../../../../Modules/docs/redundancy-report.md).

Indice filosofia wizard tema vs modulo Filament (quando rilevante): [`architecture/wizard-parity.md`](../../architecture/wizard-parity.md), [`architecture/wizard-step-visibility.md`](../../architecture/wizard-step-visibility.md).

## Percorso consigliato (flusso utente — quattro step)

Ordine naturale degli step rispetto a Design Comuni “segnalazione crea”:

| Step Design Comuni | Pagina tema (comparison / gap) |
|--------------------|-------------------------------|
| 01 Privacy | [segnalazione-01-privacy-design-comuni-vs-local.md](segnalazione-01-privacy-design-comuni-vs-local.md) |
| 02 Dati | [segnalazione-02-dati-design-comuni-vs-local.md](segnalazione-02-dati-design-comuni-vs-local.md) |
| 03 Riepilogo | [segnalazione-03-riepilogo-design-comuni-vs-local.md](segnalazione-03-riepilogo-design-comuni-vs-local.md) |
| (infra / cross-step) | vedi blocchi “Parity infra” sotto |

## Parity infra (si sovrappongono allo step-wise: usarle come contratti tecnici)

- [segnalazione-visual-parity-correction-plan.md](segnalazione-visual-parity-correction-plan.md) — piano generale correzioni UI
- [segnalazione-wizard-cta-parity.md](segnalazione-wizard-cta-parity.md) — pulsanti/avanzamenti
- [segnalazione-map-and-section-spacing-parity.md](segnalazione-map-and-section-spacing-parity.md) — layout mappa/sezioni
- [segnalazione-crea-header-and-map-visual-regression-contract.md](segnalazione-crea-header-and-map-visual-regression-contract.md)
- [segnalazione-crea-navbar-green-contract.md](segnalazione-crea-navbar-green-contract.md)
- [segnalazione-privacy-parity-audit.md](segnalazione-privacy-parity-audit.md) — audit focalizzato privacy
- [segnalazione-runtime-asset-integrity.md](segnalazione-runtime-asset-integrity.md) — integrità JS/CSS caricati
- [segnalazione-local-html-class-token-table.md](segnalazione-local-html-class-token-table.md), [segnalazione-html-samples-class-token-extraction.md](segnalazione-html-samples-class-token-extraction.md)
- [segnalazione-riepilogo-reference-gap-plan.md](segnalazione-riepilogo-reference-gap-plan.md)

## Regola DRY per nuovi documenti tema

Prima di aggiungere un altro `.md` su parity segnalazione: verificare se il contenuto è un **aggiunto incremental** di una delle pagine step (01–03) o infra sopra — in quel caso preferire una **nuova sezione** nello stesso file esistente e link dall’[`index della wiki`](../index.md).

## Collegamenti modulo Fixcity

- Confronto ad alto livello modulo: [`segnalazione-design-comuni-comparison.md`](../../../../Modules/Fixcity/docs/wiki/concepts/segnalazione-design-comuni-comparison.md).

