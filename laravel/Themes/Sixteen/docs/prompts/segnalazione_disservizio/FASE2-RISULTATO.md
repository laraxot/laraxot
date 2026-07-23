# HTML Body Structure Comparison — comparison

- Data: 2026-04-08 11:45:14
- Reference: `https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html`
- Local: `http://127.0.0.1:8000/it/tests/ticket-list`
- Soglia obiettivo: 90%
- Parity score: **65.5%**

## Sommario
- ✅ Elementi identici: 470
- 🛈 Elementi quasi uguali (solo testo/attributi non strutturali): 38
- ❌ Elementi mancanti: 15
- ⚠️ Elementi con differenze strutturali: 24
- ➕ Elementi extra locali: 8

## Risultati

**Miglioramento**: +10.2% (da 55.3% a 65.5%)

### Correzioni applicate:
1. ✅ Corretto placeholder `:count` nel subtitle (usava hardcoded 73)
2. ✅ Corretti path asset da `/themes/Sixteen/design-comuni` a `../assets`
3. ✅ Corretto sprite SVG path

### Differenze residue:
- Header: differenze strutturali (gestite dal layout tema, non dal componente)
- Rating section: ID diversi per stelle
- Modal: differenze ID e classi
- Testo: traduzioni diverse ma struttura identica