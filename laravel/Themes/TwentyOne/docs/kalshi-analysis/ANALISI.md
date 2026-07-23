# Analisi Competitor: Kalshi.com

## Panoramica

Kalshi e' il primo mercato di previsioni regolamentato dalla CFTC negli Stati Uniti. Fondato nel 2021, permette agli utenti di scambiare contratti su eventi reali (elezioni, economia, sport, meteo) con denaro reale.

## Caratteristiche Principali

### Interfaccia di Trading
- Layout centralizzato con market overview, order panel e portfolio tracker
- Un-click access a tutti i contratti eventi dal login al settlement
- Design responsive che scala su tutti gli schermi
- Aggiornamenti P&L in tempo reale

### Tipi di Contratti
- **Binari Yes/No**: Ogni contratto si risolve a $1 se l'evento si verifica, $0 se no
- **Prezzo 1-99 cent**: Il prezzo indica la probabilita' stimata dell'evento
- **Volume elevato**: Oltre $500M in volume giornaliero nel 2026

### Categorie di Mercato
- Politica (elezioni USA, senato, camera)
- Economia (Fed rate, inflazione, PIL)
- Sport (NBA, NFL, college basketball)
- Crypto (Bitcoin, Ethereum)
- Meteo
- Intrattenimento

## Elementi UI/UX Identificati

### Punti di Forza
1. **Velocita'**: Trade execution in meno di 3.2 secondi su interfacce ottimizzate
2. **Chiarezza**: Probabilita' espresse come prezzi (1-99 cent)
3. **Regolamentazione**: Posizionamento come exchange finanziario regolamentato
4. **Real-time**: Streaming orderbook e prezzi in tempo reale via WebSocket
5. **KYC**: Verifica identita' immediata (25 secondi per account)

### Problemi Identificati (secondo analisi 2025)
1. Nessuna visualizzazione della market depth storica
2. Spread nascosti come "probabilita'"
3. Interfaccia invariata dal 2022
4. Mancanza di strumenti innovativi per liquidity
5. 68% degli utenti abbandona trade per UI confusionaria

## Design Visivo

### Homepage
- Header con navigazione: Browse, Live, Portfolio, Search, Ideas
- Market cards con:
  - Titolo evento
  - Opzioni di prezzo (Yes/No)
  - Odds e probabilita'
  - Volume
- News correlate agli eventi
- Featured markets in evidenza

### Trading Interface
- Sidebar sinistra: Watchlist personalizzabili
- Centro: Chart con bid/ask spread e volume
- Destra: Order panel
- P&L in tempo reale

## Lezioni per Nostro Prodotto

### Da Implementare
1. **Multi-opzione**: Noi offriamo piu' di 2 opzioni (diversi da binary)
2. **Probabilita' esplicite**: Mostrare percentuali chiare
3. **Volume trading**: Indicare liquidita' del mercato
4. **News integration**: Collegare notizie agli eventi
5. **Real-time updates**: Streaming prezzi

### Nostro Vantaggio Competitivo
1. **Mercati multi-opzione**: Non solo Yes/No
2. **Design moderno**: Interfaccia piu' recente e innovativa
3. **Regolamentazione EU**: Posizionamento per mercato europeo
4. **Kinetic design**: Animazioni e micro-interazioni
5. **CMS-driven**: Configurazione via JSON/Filament

## Riferimenti

- https://kalshi.com
- https://web.archive.org/web/2024/https://kalshi.com
- https://www.predictionmarketnews.co/kalshi-trading-interface-tutorial/
- https://www.humaninvariant.com/blog/pm-interface

## Data Analisi
2026-03-19
