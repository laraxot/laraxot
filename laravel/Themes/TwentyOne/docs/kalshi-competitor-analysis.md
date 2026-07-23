# Analisi Kalshi.com - Competitor Study

## Panoramica

**Data analisi**: 2026-03-19  
**Fonte**: web.archive.org (versione Gennaio 2024)  
**URL**: https://kalshi.com  

---

## Elementi Chiave Identificati

### 1. Struttura Header

- **Navigazione**: Events, Financials, API, Learn
- **CTA**: Sign up / Log in
- **Stile**: Minimal, dark-themed, professionale

### 2. Hero Section

- **Titolo**: "Trade on the outcome of events"
- **CTA**: "Get access"
- **Immagine featured**: Jobs numbers in Dec 2023

### 3. Market Cards (Elemento Chiave)

Ogni market card mostra:
- **Immagine** (serie PNG/WebP)
- **Titolo** del mercato
- **Forecast** (es: "172kforecast")
- **Trend** (es: "↓4,585")

### 4. Categorie Visibili

- Jobs numbers
- Apple Vision Pro release date
- New York Times vs OpenAI
- Meissner effect
- Fed rate cut

### 5. Footer

- Disclaimer legale
- Regulatory: "U.S. regulatory oversight by the CFTC"
- Stats: "120M+ contracts", "4M+ open interest"

---

## Elementi UI da Replicare

### A. Market Card Design

```html
<!-- Struttura base -->
<div class="market-card">
  <img src="series-image.webp" alt="Market" />
  <h3>Market Title</h3>
  <div class="forecast">
    <span class="value">172k</span>
    <span class="trend down">↓4,585</span>
  </div>
</div>
```

### B. Color Palette

- **Background**: Dark (#0a0e1a o simile)
- **Primary**: Blue (#3b82f6)
- **Success**: Green (#22c55e)
- **Danger**: Red (#ef4444)
- **Text**: White/Gray

### C. Tipografia

- Sans-serif moderno
- Font weights: Regular, Medium, Bold
- Numeri grandi per stats

### D. Immagini

- Formato: WebP
- Dimensioni: 256x256 per thumb, 3840 per hero
- Source: cloudfront.net

---

## Metriche Display

Kalshi mostra:
- **Dollar volume**: 120M+ contracts
- **Open interest**: 4M+ 
- **Trend indicators**: ↑/↓ con numeri

---

## Comparazione con Nostro Sito

| Elemento | Kalshi | Nostro Sito |
|----------|--------|-------------|
| Market cards con immagini | ✅ Si | ❌ No/Mock |
| Forecast visuale | ✅ Si | ❌ No |
| Trend indicator | ✅ Si | ❌ No |
| Dark theme | ✅ Si | ✅ Si |
| Multi-opzione | Limitato | ✅ Si |

---

## Prossimi Passi

1. **Aggiungere immagini ai mercati** dal database
2. **Creare componente market card** con:
   - Immagine
   - Titolo
   - Probabilità (percentage bar)
   - Trend indicator
3. **Popolare database** con dati reali
4. **Testare layout** simile a Kalshi

---

## Riferimenti

- Screenshot: `docs/screenshots/kalshi-2024-01.png`
- Archive URL: https://web.archive.org/web/20240105135210/https://kalshi.com/

---

## Dettagli UI Estratti

### Color Palette (CSS)

```
--kalshi-palette-yes-x10: rgba(38, 92, 255, 1)  # Blue primary
--kalshi-palette-no-x10: rgba(170, 0, 255, 1)    # Purple
--kalshi-palette-brand-x10: rgba(0, 210, 150, 1) # Teal/Green
--kalshi-palette-critical-x10: rgba(217, 0, 72, 1) # Red
--kalshi-palette-success-x10: rgba(0, 166, 69, 1)  # Green
--kalshi-palette-surface-x10: rgba(255, 255, 255, 1) # White
--kalshi-palette-fill-x10: rgba(0, 0, 0, 0.9) # Dark
```

### Typography

- Font: Inter (var(--inter-font))
- Heading: 58px, weight 600
- Body: 14-16px, weight 400-500

### Market Card Struttura

```html
<div class="market-card">
  <img src="series-images-webp/XXX.webp" />
  <div class="badge">Featured</div>
  <div class="title">Jobs numbers in Dec 2023</div>
  <div class="forecast">
    <span class="value">172k</span>
    <span class="trend down">↓4,585</span>
  </div>
</div>
```

### Trend Indicators

- Up: `↑` + verde/blue
- Down: `↓` + rosso/purple

### Featured Markets (2024)

1. Jobs numbers (PAYROLLS)
2. Apple Vision Pro release date (VISIONPRO)
3. New York Times wins OpenAI lawsuit (NYTOAI)
4. Meissner effect (MEISSNER)
5. Fed rate cut (RATECUT)
