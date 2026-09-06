---
title: "Quotation Module — Doctrine"
type: doctrine
tags: [quotation, preventivi, module-doctrine]
created: 2026-09-05
updated: 2026-09-05
qmd: "Quotation module doctrine BMAD analysis purpose religion philosophy policy why zen gap enhancements split merge"
related:
  - "../../Xot/docs/module.md"
  - "../../Customer/docs/module.md"
  - "../../Catalog/docs/module.md"
  - "./gestionale-commesse-port.md"
---

# Quotation Module — Doctrine

## Scope (Scopo)

Quotation gestisce la generazione e gestione di preventivi e proposte commerciali. Crea preventivi da catalogo, applica regole di pricing, gestisce template, e traccia lo stato del preventivo (bozza, inviato, accettato, rifiutato, scaduto).

## Religion (Religione)

**"Un preventivo è una promessa. Deve essere accurato, conforme, e tracciabile."** La convinzione non negoziata è che ogni numero in un preventivo è un impegno verso il cliente. Errori di prezzo non sono ammessi, regole di sconto sono applicate uniformemente, ogni cambio è versionato.

## Philosophy (Filosofia)

- **Template-driven**: i preventivi sono generati da template configurabili
- **Pricing rules engine**: regole di sconto, listino, valuta applicate automaticamente
- **Approval workflow**: soglie per approvazione automatica vs manuale
- **Versioning**: ogni revisione del preventivo è una nuova versione, mai overwrite
- **State machine**: Bozza → Inviato → Accettato/Rifiutato/Scaduto

## Policy (Politica)

- Regole di prezzo in configurazione, mai hardcoded
- Approval obbligatorio per preventivi sopra soglia
- Soft delete, mai hard delete
- Stato cambia solo tramite Action dedicata
- Conversione automatica in WorkOrder su accettazione

## Why (Perché)

Quotation esiste perché la preventivazione ha regole complesse di pricing, sconti, validità, e workflow di approvazione che non possono essere gestite inline. Quotation è il garante che ogni offerta commerciale sia accurata e tracciabile.

## Zen

*"Un preventivo, una promessa. Calcolata con rigore, mantenuta con onestà."*

## Gap

- 20 Actions (buono)
- Template management limitato
- Nessun approval workflow implementato
- Test coverage scarso
- Nessuna integrazione con Catalog per pricing real-time
- Mancanza di dynamic pricing rules

## Add

- Template management drag-and-drop
- Approval workflow con role-based gates
- Dynamic pricing rules engine
- Quote comparison e revision history visuale
- Real-time pricing da Catalog
- Integrazione con e-signature per accettazione
- Generazione PDF automatica con template brandizzati

## Split/Merge

**Mantenere separato.** Quotation è sales-specific e distinto dal processing operativo. Tuttavia, potrebbe parzialmente fondersi con Billing per allineamento finanziario (es. conversione automatica preventivo → fattura pro-forma). Attualmente la separazione è netta.

## Future Enhancements

1. **AI-assisted quote generation**: suggerimenti automatici basati su storico cliente
2. **Interactive pricing calculator**: configuratore interattivo per preventivi complessi
3. **Quote win/loss analytics**: analisi predittiva di conversione
4. **Multi-currency quotation**: preventivi in valute multiple con tassi bloccati
5. **Smart templates**: template che si adattano al cliente in base a segmentazione
6. **E-signature integration**: accettazione digitale del preventivo
