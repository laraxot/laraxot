---
title: "Catalog Module — Doctrine"
type: doctrine
tags: [catalog, products, module-doctrine]
created: 2026-09-05
updated: 2026-09-05
qmd: "Catalog module doctrine BMAD analysis purpose religion philosophy policy why zen gap enhancements split merge"
related:
  - "../../Xot/docs/module.md"
  - "../../Quotation/docs/module.md"
  - "./gestionale-commesse-port.md"
---

# Catalog Module — Doctrine

## Scope (Scopo)

Catalog gestisce l'anagrafica prodotti: articoli, varianti, descrizioni, categorie, attributi, immagini, prezzi base. È la fonte unica di verità per ciò che l'azienda vende. Tutti gli altri moduli (Quotation, Billing, Inventory, Production) leggono da qui, mai da copie locali.

## Religion (Religione)

**"Un prodotto, una definizione, infinite contestualizzazioni."** La convinzione non negoziabile è che il prodotto è sacro: le sue caratteristiche intrinseche (SKU, descrizione, attributi) sono immutabili, mentre prezzo e disponibilità sono contestualizzati da altri moduli (Inventory, Quotation).

## Philosophy (Filosofia)

- **Single source of truth**: un solo record per prodotto
- **Versioning del catalogo**: ogni modifica è versionata per rollback
- **Faceted search**: ricerca per attributi combinati
- **SEO-friendly attributes**: slug, meta, schema.org
- **Variants e options**: varianti (taglia/colore/materiale) come relazioni

## Policy (Politica)

- SKU unico per prodotto
- Categoria gerarchica (parent/child)
- Prezzo base separato da prezzo di vendita (gestito da Quotation)
- Soft deprecate, mai hard delete
- Immagini multiple con ordine di priorità
- Attributi schemaless per flessibilità

## Why (Perché)

Catalog esiste perché senza un catalogo centralizzato, ogni modulo (Quotation, Inventory, Production) avrebbe la propria definizione di prodotto con conseguente disallineamento, errori di prezzo, e impossibilità di riportare la stessa referenza. Catalog è la base su cui si costruiscono le vendite.

## Zen

*"Un prodotto, infinite storie. Definito una volta, venduto ovunque."*

## Gap

- Solo 3 Actions (pochissimi per un dominio ampio)
- Variant management limitato a SKU base
- Ricerca/filter limitati
- Nessun bulk import/export
- Test coverage scarso
- No versioning del catalogo ancora implementato
- No product lifecycle (active/deprecated) automatizzato

## Add

- Variant management avanzato (size, color, material con regole)
- Faceted search con filtri combinati
- Bulk import/export CSV/Excel
- Catalog versioning con rollback
- Product lifecycle management (active/deprecated)
- Integrazione con motori di raccomandazione
- Sincronizzazione con marketplace (Amazon, eBay)

## Split/Merge

**Mantenere separato.** Catalog è troppo ampio e fondamentale per essere fuso con altri moduli. È la spina dorsale di tutte le attività commerciali. Eventualmente, potrebbe frammentarsi in `CatalogCore` (anagrafica) e `CatalogPricing` (regole prezzo) se la complessità cresce.

## Future Enhancements

1. **AI-powered product description**: generazione automatica descrizioni multilingua
2. **Visual search**: ricerca prodotto per immagine
3. **Recommendation engine**: suggerimenti basati su acquisti precedenti
4. **Inventory-aware catalog**: segnalazione automatica prodotti esauriti
5. **Dynamic pricing AI**: prezzi dinamici basati su domanda/stagionalità
6. **Multi-warehouse catalog**: regole di disponibilità per magazzino
