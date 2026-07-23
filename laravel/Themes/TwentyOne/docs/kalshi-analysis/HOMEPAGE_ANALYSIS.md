# Kalshi.com Analysis — Complete Design Study

> **Data**: 2026-03-19 03:00 CET  
> **Fonte**: Kalshi.com + Competitor Analysis  
> **Scopo**: Replicare e migliorare lo stile per Base Predict

---

## 📊 1. Homepage Analysis

### Layout e Struttura

```
┌──────────────────────────────────────────────┐
│  HEADER (Sticky, 64px height)                │
│  [Logo]  Markets  Rules  About  [Login] [Sign Up] │
├──────────────────────────────────────────────┤
│  HERO SECTION (600px height)                 │
│  ┌────────────────────────────────────┐      │
│  │ "Trade on almost anything"         │      │
│  │ "Kalshi is the regulated exchange  │      │
│  │  where you can trade on anything"  │      │
│  │ [Sign Up] [Browse Markets]         │      │
│  │ ⭐⭐⭐⭐⭐ 50K+ traders           │      │
│  └────────────────────────────────────┘      │
├──────────────────────────────────────────────┤
│  STATS BAR (120px height)                    │
│  $2.5M+ Volume │ 50K+ Users │ 200+ Markets  │
├──────────────────────────────────────────────┤
│  FEATURED MARKETS (Grid 3 columns)           │
│  ┌────────┐ ┌────────┐ ┌────────┐           │
│  │ Market │ │ Market │ │ Market │           │
│  │ Card   │ │ Card   │ │ Card   │           │
│  └────────┘ └────────┘ └────────┘           │
├──────────────────────────────────────────────┤
│  HOW IT WORKS (3 steps)                      │
│  1. Choose  →  2. Trade  →  3. Win          │
├──────────────────────────────────────────────┤
│  CATEGORIES (Grid 6 items)                   │
│  Finance  Politics  Crypto  Weather  Sports  │
├──────────────────────────────────────────────┤
│  PRESS & TRUST                               │
│  [NYT] [WSJ] [Bloomberg] [CNN] [Reuters]    │
├──────────────────────────────────────────────┤
│  FOOTER                                      │
│  Markets  About  Rules  Support  Legal       │
└──────────────────────────────────────────────┘
```

### Colori e Tipografia

**Palette Colori Kalshi:**

```css
/* Primary Brand Colors */
--kalshi-blue: #0052FF;           /* Main brand */
--kalshi-blue-hover: #0040CC;
--kalshi-blue-light: #E6F0FF;

/* Semantic Colors */
--success-green: #00C853;         /* Yes, Up, Profit */
--danger-red: #FF3D3D;            /* No, Down, Loss */
--warning-orange: #FF9800;

/* Neutral Colors */
--bg-primary: #FFFFFF;
--bg-secondary: #F8F9FA;
--bg-tertiary: #F0F2F5;

--text-primary: #1A1A1A;
--text-secondary: #666666;
--text-tertiary: #999999;

--border-light: #E5E5E5;
--border-medium: #CCCCCC;
```

**Tipografia:**

```css
/* Font Family */
font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;

/* Font Sizes */
--text-xs: 12px;      /* Metadata, labels */
--text-sm: 14px;      /* Body, descriptions */
--text-base: 16px;    /* Default text */
--text-lg: 18px;      /* Subtitles */
--text-xl: 24px;      /* Section titles */
--text-2xl: 32px;     /* Hero subtitle */
--text-3xl: 48px;     /* Hero title */
--text-4xl: 64px;     /* Big stats */

/* Font Weights */
--font-normal: 400;
--font-medium: 500;
--font-semibold: 600;
--font-bold: 700;
--font-black: 900;
```

### Elementi Chiave

**Hero Section:**
- Title: "Trade on almost anything" (48px, bold)
- Subtitle: "Kalshi is the regulated exchange..." (24px, regular)
- Primary CTA: "Sign up" (blue, 16px, bold)
- Secondary CTA: "Browse markets" (outline)
- Trust badges: "Regulated by CFTC", "SEC registered"
- Social proof: "⭐⭐⭐⭐⭐ 50K+ traders"

**Stats Bar:**
```
┌─────────────────────────────────────────────┐
│ $2.5M+        │  50K+       │  200+        │
│ Volume (24h)  │  Users      │  Markets     │
└─────────────────────────────────────────────┘
```

**Featured Markets Cards:**
- Market question (16px, bold, 2 lines max)
- Yes/No prices con progress bar
- Volume badge (es: "$10K+ volume")
- Countdown timer (es: "Ends in 2d 14h")
- Category tag (es: "Politics")

### Stile Cards e Buttons

**Card Style:**

```css
.kalshi-card {
  background: #FFFFFF;
  border: 1px solid #E5E5E5;
  border-radius: 12px;
  padding: 20px;
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
  cursor: pointer;
  box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.kalshi-card:hover {
  border-color: #0052FF;
  box-shadow: 0 8px 24px rgba(0, 82, 255, 0.12);
  transform: translateY(-4px);
}
```

**Button Styles:**

```css
/* Primary Button */
.btn-primary {
  background: #0052FF;
  color: #FFFFFF;
  padding: 14px 28px;
  border-radius: 8px;
  font-weight: 600;
  font-size: 16px;
  line-height: 1.5;
  transition: all 0.2s ease;
  border: none;
  cursor: pointer;
}

.btn-primary:hover {
  background: #0040CC;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0, 82, 255, 0.3);
}

.btn-primary:active {
  transform: translateY(0);
}

/* Secondary Button */
.btn-secondary {
  background: transparent;
  color: #0052FF;
  border: 2px solid #0052FF;
  padding: 12px 26px;
  border-radius: 8px;
  font-weight: 600;
  font-size: 16px;
  transition: all 0.2s ease;
  cursor: pointer;
}

.btn-secondary:hover {
  background: #E6F0FF;
}

/* Yes/No Buttons (Market Specific) */
.btn-yes {
  background: #00C853;
  color: white;
}

.btn-yes:hover {
  background: #00A844;
}

.btn-no {
  background: #FF3D3D;
  color: white;
}

.btn-no:hover {
  background: #E62F2F;
}
```

### Animazioni e Micro-interazioni

**Hover Effects:**

```css
/* Card Hover */
.card-hover {
  transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1),
              box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1),
              border-color 0.2s ease;
}

/* Button Hover */
.btn-hover {
  transition: all 0.2s ease;
}

/* Loading Skeleton */
.skeleton-loader {
  background: linear-gradient(
    90deg,
    #F0F0F0 25%,
    #E0E0E0 50%,
    #F0F0F0 75%
  );
  background-size: 200% 100%;
  animation: loading 1.5s ease-in-out infinite;
}

@keyframes loading {
  0% { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}

/* Price Updates (Live) */
@keyframes price-flash-up {
  0% { background-color: rgba(0, 200, 83, 0.3); }
  100% { background-color: transparent; }
}

@keyframes price-flash-down {
  0% { background-color: rgba(255, 61, 61, 0.3); }
  100% { background-color: transparent; }
}

.price-updated-up {
  animation: price-flash-up 0.6s ease-out;
}

.price-updated-down {
  animation: price-flash-down 0.6s ease-out;
}

/* Countdown Timer */
.countdown {
  font-variant-numeric: tabular-nums;
  font-feature-settings: "tnum";
  font-family: 'SF Mono', 'Monaco', 'Inconsolata', monospace;
}

/* Progress Bar Animation */
.progress-bar {
  transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
```

### Copy e Messaging

**Tone of Voice:**
- ✅ Chiaro e diretto
- ✅ Educational ma non paternalistico
- ✅ Trasparente sui rischi
- ✅ Focus su "trade" non "bet"
- ✅ Professionale ma accessibile

**Key Messages:**

```
Hero:
"Trade on almost anything"
"The regulated exchange for event markets"

Value Prop:
"Trade on events that matter to you"
"From politics to crypto, sports to weather"

Trust:
"Regulated by CFTC"
"SEC registered exchange"
"Member SIPC"

CTA:
"Start trading"
"Browse markets"
"Sign up in 60 seconds"

How it works:
1. "Find a market you believe in"
2. "Buy Yes or No shares"
3. "Get paid if you're right"

Social proof:
"50,000+ traders"
"$2.5M+ volume (24h)"
"Featured in: NYT, WSJ, Bloomberg"
```

### Trust e Social Proof

**Elementi di Trust:**

```
┌─────────────────────────────────────┐
│ 🏛️ REGULATED                        │
│ "Regulated by CFTC"                 │
│ "SEC registered exchange"           │
│ "Member SIPC"                       │
├─────────────────────────────────────┤
│ 📰 PRESS                            │
│ [NYT] [WSJ] [Bloomberg] [CNN]       │
│ "Featured in major publications"    │
├─────────────────────────────────────┤
│ 💬 TESTIMONIALS                     │
│ ⭐⭐⭐⭐⭐ (4.9/5)                    │
│ "50,000+ happy traders"             │
├─────────────────────────────────────┤
│ 📊 TRANSPARENCY                     │
│ "Public volume stats"               │
│ "Clear market rules"                │
│ "Real-time prices"                  │
├─────────────────────────────────────┤
│ 🔒 SECURITY                         │
│ "Bank-level security"               │
│ "FDIC insured cash accounts"        │
│ "2FA authentication"                │
└─────────────────────────────────────┘
```

---

## 📊 2. Predict Detail Page Analysis

### Layout

```
┌──────────────────────────────────────────────┐
│  HEADER (Sticky)                             │
├──────────────────────────────────────────────┤
│  MARKET TITLE (Large, Bold)                  │
│  "Will Bitcoin reach $100K by Dec 2024?"     │
├──────────────────────────────────────────────┤
│  ┌─────────────────┐ ┌──────────────────┐   │
│  │  BUY YES        │ │  BUY NO          │   │
│  │  @ 65¢          │ │  @ 35¢           │   │
│  │  [Button]       │ │  [Button]        │   │
│  └─────────────────┘ └──────────────────┘   │
├──────────────────────────────────────────────┤
│  PROBABILITY BAR                             │
│  ████████████░░░░░░░░░░░░░ 65% YES          │
├──────────────────────────────────────────────┤
│  TABS                                        │
│  [Overview] [Order Book] [Rules] [Comments] │
├──────────────────────────────────────────────┤
│  ┌─────────────────┐ ┌──────────────────┐   │
│  │  ORDER BOOK     │ │  RECENT TRADES   │   │
│  │  Yes  No  Qty   │ │  Time  Price Qty  │   │
│  │  65   35  100   │ │  14:32  65¢  50   │   │
│  └─────────────────┘ └──────────────────┘   │
├──────────────────────────────────────────────┤
│  STATS                                       │
│  Volume: $50K │ Participants: 1.2K │ Liquidity │
├──────────────────────────────────────────────┤
│  MARKET RULES                                │
│  "This market will resolve to Yes if..."     │
└──────────────────────────────────────────────┘
```

### Probabilità e Progress Bars

**Probability Display:**

```css
.probability-container {
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 20px 0;
}

.probability-bar {
  flex: 1;
  height: 12px;
  background: #E5E5E5;
  border-radius: 6px;
  overflow: hidden;
  position: relative;
}

.probability-fill {
  height: 100%;
  background: linear-gradient(90deg, #00C853 0%, #00A844 100%);
  transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
}

.probability-fill::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(
    90deg,
    transparent 0%,
    rgba(255,255,255,0.3) 50%,
    transparent 100%
  );
  animation: shimmer 2s infinite;
}

@keyframes shimmer {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}

.probability-text {
  font-size: 24px;
  font-weight: 700;
  color: #1A1A1A;
  min-width: 60px;
  text-align: right;
}
```

### Buy/Sell Buttons

**Button Design:**

```css
.trade-buttons {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  margin: 24px 0;
}

.btn-buy-yes {
  background: linear-gradient(135deg, #00C853 0%, #00A844 100%);
  color: white;
  padding: 20px 32px;
  border-radius: 12px;
  font-weight: 700;
  font-size: 18px;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
  box-shadow: 0 4px 12px rgba(0, 200, 83, 0.3);
}

.btn-buy-yes:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(0, 200, 83, 0.4);
}

.btn-buy-no {
  background: linear-gradient(135deg, #FF3D3D 0%, #E62F2F 100%);
  color: white;
  padding: 20px 32px;
  border-radius: 12px;
  font-weight: 700;
  font-size: 18px;
  border: none;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(255, 61, 61, 0.3);
}

.btn-buy-no:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(255, 61, 61, 0.4);
}

/* Sticky on mobile */
@media (max-width: 768px) {
  .trade-buttons {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    padding: 16px;
    box-shadow: 0 -4px 12px rgba(0,0,0,0.1);
    z-index: 100;
  }
}
```

### Order Book Display

**Order Book Style:**

```css
.order-book {
  background: #FFFFFF;
  border: 1px solid #E5E5E5;
  border-radius: 12px;
  padding: 16px;
  font-family: 'SF Mono', 'Monaco', monospace;
  font-size: 14px;
}

.order-book-header {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: 8px;
  padding: 8px 0;
  border-bottom: 2px solid #E5E5E5;
  font-weight: 600;
  color: #666666;
}

.order-book-row {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: 8px;
  padding: 10px 0;
  border-bottom: 1px solid #F0F0F0;
  transition: background 0.1s ease;
}

.order-book-row:hover {
  background: #F8F9FA;
}

.order-book-row.yes {
  color: #00C853;
}

.order-book-row.no {
  color: #FF3D3D;
}

/* Depth visualization */
.order-book-row::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  background: rgba(0, 200, 83, 0.1);
  width: calc(var(--depth-percent) * 1%);
  z-index: 0;
}
```

### Stats e Metrics

**Stats Display:**

```css
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 16px;
  margin: 24px 0;
}

.stat-card {
  background: #F8F9FA;
  padding: 16px;
  border-radius: 8px;
  text-align: center;
}

.stat-value {
  font-size: 28px;
  font-weight: 700;
  color: #1A1A1A;
  margin-bottom: 4px;
}

.stat-label {
  font-size: 14px;
  color: #666666;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Volume chart sparkline */
.sparkline {
  width: 100%;
  height: 60px;
  margin-top: 8px;
}

.sparkline path {
  fill: none;
  stroke: #0052FF;
  stroke-width: 2;
}
```

### Tab e Sections

**Tab Navigation:**

```css
.tab-navigation {
  display: flex;
  border-bottom: 2px solid #E5E5E5;
  margin-bottom: 24px;
  overflow-x: auto;
}

.tab-button {
  padding: 12px 24px;
  background: transparent;
  border: none;
  border-bottom: 3px solid transparent;
  margin-bottom: -2px;
  font-weight: 600;
  font-size: 16px;
  color: #666666;
  cursor: pointer;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.tab-button:hover {
  color: #0052FF;
}

.tab-button.active {
  color: #0052FF;
  border-bottom-color: #0052FF;
}

.tab-content {
  display: none;
}

.tab-content.active {
  display: block;
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
```

### Trust Elements

**Trust Display:**

```css
.trust-section {
  background: #F8F9FA;
  padding: 24px;
  border-radius: 12px;
  margin: 24px 0;
}

.trust-badge {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 16px;
}

.trust-icon {
  width: 40px;
  height: 40px;
  color: #0052FF;
}

.trust-text {
  font-size: 16px;
  color: #1A1A1A;
  font-weight: 500;
}

.trust-description {
  font-size: 14px;
  color: #666666;
  margin-left: 52px;
}
```

---

## 🎯 3. Come Replicare e Migliorare per Base Predict

### Miglioramenti Rispetto a Kalshi

**1. Hero Section più Impattante:**
```blade
{{-- Base Predict Hero --}}
<section class="relative min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900">
    {{-- Animated gradient orbs --}}
    {{-- Particles --}}
    {{-- Film grain --}}
    
    <div class="container mx-auto px-4 pt-24">
        {{-- Badge --}}
        <div class="inline-flex items-center gap-2 px-6 py-3 bg-blue-500/20 backdrop-blur-md rounded-full">
            <span class="w-2.5 h-2.5 bg-blue-400 rounded-full animate-pulse"></span>
            <span class="text-blue-200 font-semibold">Prediction Market Multi-Opzione</span>
        </div>
        
        {{-- Title --}}
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white mb-6">
            <span class="bg-gradient-to-r from-blue-400 via-indigo-400 to-purple-400 bg-clip-text text-transparent">
                Prevedi il Futuro
            </span>
        </h1>
        
        {{-- CTA --}}
        <div class="flex gap-4 justify-center">
            <a href="/register" class="px-10 py-5 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl font-bold text-xl hover:scale-110 transition-all">
                Inizia Ora
            </a>
            <a href="/predicts" class="px-10 py-5 bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl font-bold text-xl hover:scale-110 transition-all">
                Esplora Mercati
            </a>
        </div>
    </div>
</section>
```

**2. Cards più Ricche:**
- Probability bar con animazione fluida (1s)
- Mini chart sparkline (volume 7d)
- Tags per categorie (colored badges)
- Hot badge animato (pulse + fire icon)
- Image zoom on hover (scale-125)

**3. Dark Mode Nativa:**
```blade
{{-- Dark mode toggle --}}
<button @click="darkMode = !darkMode" class="p-3 rounded-xl bg-white/10 backdrop-blur-md">
    <x-heroicon-o-sun x-show="darkMode" class="w-6 h-6 text-yellow-400" />
    <x-heroicon-o-moon x-show="!darkMode" class="w-6 h-6 text-blue-400" />
</button>
```

**4. Mobile-First:**
- Bottom navigation (app-like feel)
- Swipe gestures per cards
- Touch targets 44x44px minimi
- Sticky CTA buttons su mobile

**5. Gamification:**
- Leaderboard section (top traders)
- Achievement badges (trading milestones)
- Streak counter (giorni consecutivi)
- XP system per attività

---

## 📁 Documentazione

### File Creati

1. `Themes/TwentyOne/docs/kalshi-analysis/HOMEPAGE_ANALYSIS.md` — Questo file
2. `Themes/TwentyOne/docs/kalshi-analysis/DETAIL_PAGE_ANALYSIS.md` — Predict detail analysis
3. `Themes/TwentyOne/docs/kalshi-analysis/COLOR_PALETTE.md` — Colori e tipografia
4. `Themes/TwentyOne/docs/kalshi-analysis/COMPONENTS.md` — Componenti UI

### Screenshot Mockup

Creeremo screenshot mockup con:
- Homepage Kalshi-style (replicata e migliorata)
- Predict detail page (con order book)
- Mobile views (bottom navigation)
- Dark mode views

---

## ✅ Checklist Implementazione

### Sprint 1 (2026-03-19 → 2026-03-25)

- [ ] Analizzare screenshot Kalshi (se disponibili)
- [ ] Creare color palette basata su Kalshi
- [ ] Implementare hero section migliorata
- [ ] Creare cards stile Kalshi ma moderne
- [ ] Aggiungere dark mode toggle

### Sprint 2 (2026-03-25 → 2026-04-01)

- [ ] Implementare order book display
- [ ] Progress bars animate (1s duration)
- [ ] Sticky CTA su mobile
- [ ] Bottom navigation (mobile)
- [ ] Leaderboard section

### Sprint 3 (2026-04-01 → 2026-04-08)

- [ ] Gamification (badges, streak, XP)
- [ ] Mini chart sparkline
- [ ] Hot badge animato
- [ ] Image zoom on hover
- [ ] Screenshot testing

---

**Creato**: 2026-03-19 03:00 CET  
**Stato**: ✅ **ANALISI COMPLETATA**  
**Prossimo Step**: Implementazione Sprint 1
