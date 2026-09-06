---
title: "Bom Module — Doctrine"
type: doctrine
tags: [bom, distinta-base, module-doctrine]
created: 2026-09-05
updated: 2026-09-05
qmd: "Bom module doctrine BMAD analysis purpose religion philosophy policy why zen gap enhancements split merge"
related:
  - "../../Xot/docs/module.md"
  - "../../Catalog/docs/module.md"
  - "../../Production/docs/module.md"
---

# Bom Module — Doctrine

## Scope (Scopo)

Bom (Bill of Materials) gestisce le distinte base dei prodotti: elenco componenti, quantità, materiali, procedure di assemblaggio. Definisce ciò che compone un prodotto finito, permettendo calcolo costi, pianificazione materiali, e tracciamento versioni.

## Religion (Religione)

**"Una distinta, una verità."** La convinzione non negoziabile è che la distinta base è la definizione fondamentale di un prodotto: senza di essa, non si sa cosa contiene, quanto costa, o come si assembla. Ogni modifica è versionata, ogni versione è tracciata.

## Philosophy (Filosofia)

- **Hierarchical structure**: BOM ad albero con sotto-livelli illimitati
- **Versioning**: ogni modifica crea nuova versione, storico preservato
- **Material tracking**: ogni componente tracciato con SKU e quantità
- **Cost rollup**: costo totale calcolato ricorsivamente dai componenti
- **Effective dating**: BOM valido da/valido a per gestione versioni

## Policy (Politica)

- Ogni BOM ha almeno un componente
- Versione obbligatoria su ogni modifica
- Costo calcolato automaticamente dai componenti
- Approvazione richiesta per BOM con impatto costi > soglia
- Soft delete, mai hard delete

## Why (Perché)

Bom esiste perché la distinta base è il ponte tra Catalog (cosa è il prodotto) e Production (come si produce). Senza BOM, non c'è tracciabilità materiali, non c'è calcolo costi accurato, non c'è pianificazione produzione.

## Zen

*"Ogni prodotto è una somma di parti. Ogni parte è tracciata."*

## Gap

- 0 Actions (quasi vuoto)
- Nessun cost rollup engine
- Nessuna integrazione con Inventory per consumabili
- Nessun versioning comparison tool
- Test coverage assente

## Add

- Cost rollup engine ricorsivo
- Integration con Inventory per consumabili automatica
- Version comparison tool (diff tra versioni BOM)
- Multi-level BOM con esplosione componenti
- Integrazione con Production per scheduling materiali
- Calcolo automatico lead time basato su BOM

## Split/Merge

**Mantenere separato.** Bom è un modulo tecnico distinto che collega Catalog e Production. Non ha sovrapposizioni significative con altri moduli. Mantenere come-is con possibile estrazione di `BomEngine` se la logica di calcolo cresce.

## Future Enhancements

1. **Multi-BOM per variante**: distinte diverse per stessa referenza (colori/taglie)
2. **Substitution management**: gestione sostituzioni componenti
3. **Vendor-managed BOM**: fornitori possono contribuire con BOM propri
4. **BOM cost simulation**: "what-if" per costi alternativi
5. **Engineering change management**: workflow per modifiche BOM con approvazione
6. **BOM AI assistant**: suggerimenti ottimizzazione componenti/costi
