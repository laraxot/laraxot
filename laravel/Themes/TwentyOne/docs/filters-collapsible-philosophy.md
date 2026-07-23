# Filtri collassabili — filosofia UI/UX

## Scopo

Ridurre l'occupazione verticale dei filtri sulla pagina lista mercati (`/it/predicts`), mantenendo accessibilità e usabilità.

## Decisione

- **Nascosti di default**: categorie, search e ordinamento sono in un pannello collassabile
- **Toggle "Filtri e ordina"**: un solo pulsante per espandere/comprimere
- **Ordina**: Hot, Nuovi, Volume, Partecipanti, Scadenza — già presenti, ora nel pannello

## Motivazione

- Polymarket/Kalshi: filtri compatti, spesso in dropdown o tab
- Ridurre scroll iniziale per vedere i mercati
- Mobile-first: meno spazio verticale su schermi piccoli

## Implementazione

- `filtersOpen: false` — stato iniziale chiuso
- `x-collapse` (Alpine) per animazione fluida
- `aria-expanded` e `aria-controls` per accessibilità
