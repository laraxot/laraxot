# Kalshi.com Design Analysis

**Data**: 2026-03-19  
**Scopo**: Studio del design di Kalshi.com per ispirazione e miglioramento UI/UX  
**Tema di riferimento**: TwentyOne

---

## 1. Panoramica Kalshi

Kalshi è la principale piattaforma americana di prediction market regolamentata CFTC. Fondata nel 2018 da ex-studenti MIT.

### Caratteristiche Principali
- **Regolamentazione**: Unica piattaforma CFTC-regolamentata per event contract
- **Tipologia mercati**: Politica, economia, sport, crypto, meteo
- **Interfaccia**: Exchange-style con focus su probabilità e volumi
- **Volume**: $500M+ in daily event contract volume (2026)

---

## 2. Elementi UI/UX Chiave

### 2.1 Homepage Layout

```
┌─────────────────────────────────────────────────────────────┐
│  [Logo]   Browse  Live   Portfolio   Search   [Login]    │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  ## WTI oil price on Mar 19, 2026?                        │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ $98 or above    │ 1.97x │ 48%                      │  │
│  │ $97 or above    │ 1.44x │ 67%                      │  │
│  │ $273,152 vol    │ 13 markets                           │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                             │
│  ## Men's College Basketball Champion                      │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ Duke       │ 4.73x │ 20%                             │  │
│  │ Arizona    │ 5.25x │ 18%                            │  │
│  │ $94M vol   │ 62 markets                             │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                             │
│  News: [contexto dell'evento]                               │
└─────────────────────────────────────────────────────────────┘
```

### 2.2 Market Card Design

**Elementi essenziali**:
1. **Titolo evento** - Bold, chiaro
2. **Opzioni di trading** - Yes/No con probabilità
3. **Odds/Payout** - Formato decimale (es. 1.97x)
4. **Probabilità** - Percentuale visibile
5. **Volume** - Denaro scambiato
6. **News** - Contesto dell'evento

### 2.3 Navigazione

- **Top Bar**: Browse, Live (mercati attivi), Portfolio, Search
- **Sidebar**: Categorie (Elections, Sports, Crypto, Economics)
- **Filter**: Da/A, Volume minimo, Status

### 2.4 Market Detail Page

```
┌─────────────────────────────────────────────────────────────┐
│  ## Which party will win the U.S. Senate?                  │
│                                                             │
│  ┌────────────────┬────────┬───────┬────────┐            │
│  │ Market         │ Pays   │ Odds  │ Volume │            │
│  ├────────────────┼────────┼───────┼────────┤            │
│  │ Republican     │ $1.90  │ 51%   │ $2.4M  │            │
│  │ Democratic     │ $1.93  │ 50%   │        │            │
│  └────────────────┴────────┴───────┴────────┘            │
│                                                             │
│  [Buy Yes] [Buy No]                                        │
│                                                             │
│  News: Democrats may have new opportunities...              │
└─────────────────────────────────────────────────────────────┘
```

---

## 3. Design Tokens Kalshi

### 3.1 Colori

| Uso | Colore | Hex |
|-----|--------|-----|
| Primary (CTA) | Verde | `#00C853` |
| Secondary | Blu | `#2979FF` |
| Background | Bianco scuro | `#FAFAFA` |
| Text Primary | Nero | `#1A1A1A` |
| Text Secondary | Grigio | `#6B7280` |
| Success | Verde chiaro | `#10B981` |
| Warning | Arancione | `#F59E0B` |

### 3.2 Tipografia

- **Font**: Inter (Google Fonts)
- **Titoli**: 600-700 weight
- **Body**: 400-500 weight
- **Numeri**: Tabular figures per allineamento

### 3.3 Spacing

- Base unit: 4px
- Padding cards: 16px (4 units)
- Gap tra elementi: 12px (3 units)

---

## 4. Pattern di Design

### 4.1 Probability Bar

```
┌────────────────────────────────────────────────────────────┐
│ ████████████████████████████████░░░░░░░░░░░░░░░░░░░░░░░░░ │
│ 48%                                                     │
└────────────────────────────────────────────────────────────┘
```

### 4.2 Market Entry

```
┌────────────────────────────────────────────────────────────┐
│ [Icon]  Titolo Evento                        $273,152 vol   │
│         Opzione A        48%    1.97x                    │
│         Opzione B        52%    1.93x                    │
│         ████████████░░░░░░░░░░░░░░░░░░░░░               │
└────────────────────────────────────────────────────────────┘
```

### 4.3 News Card

```
┌────────────────────────────────────────────────────────────┐
│ 📰 News                                                 │
│                                                        │
│ Democrats may have new opportunities to compete for     │
│ Senate seats in Alaska, Maine, North Carolina...        │
│                                                        │
│ fonte • 2h fa                                          │
└────────────────────────────────────────────────────────────┘
```

---

## 5. Differenze vs Nostro Design

### Kalshi
- Design pulito, minimalista
- Exchange-style (tabelle)
- Focus su numeri e probabilità
- News integrate nei market
- Single-page experience

### Nostro (Predict)
- Dark theme con gradienti
- Card-based layout
- Animazioni cinematiche
- CMS-driven con blocchi JSON
- Multi-opzione markets

---

## 6. Elementi da Adottare

### 6.1 UI Elements
- [ ] Probability bar con gradient
- [ ] Formato odds decimale (1.97x)
- [ ] Volume display con valuta
- [ ] News contextuali nei market
- [ ] Filtri categoria sticky

### 6.2 UX Patterns
- [ ] Lazy loading mercati
- [ ] Real-time price updates
- [ ] Quick buy/sell flow
- [ ] Portfolio summary prominente
- [ ] Search con autocomplete

### 6.3 Content Strategy
- [ ] News integration per contesto
- [ ] Market descriptions dettagliate
- [ ] Resolution criteria visibili
- [ ] Historical data accessibili

---

## 7. Riferimenti

- Sito: https://kalshi.com
- Docs: https://docs.kalshi.com
- API: https://api.kalshi.com

---

## 8. Note Tecniche

### API Kalshi Structure
```json
{
  "event_ticker": "KXACPI-2025",
  "title": "Inflation in 2025?",
  "markets": [
    {
      "market_ticker": "KXACPI-2025-YES",
      "title": "Yes",
      "yes_bid": 72,
      "yes_ask": 73,
      "liquidity": 1500000
    }
  ]
}
```

### Probability Calculation
```
probability = (100 - spread) / 100
payout = 100 / probability
```

---

*Documento creato per scopi di studio e ispirazione. Kalshi è un trademark di KalshiEX LLC.*
