# Homepage Improvement Plan — http://predict.local/it

> **Data**: 2026-03-18  
> **Obiettivo**: Rendere la homepage PERFETTA seguendo la checklist website-checklist.md  
> **Standard**: WCAG 2.2 AA, Core Web Vitals 2026, 10 Priorità Critiche (70% successo)

---

## 📊 Stato Attuale

### ✅ Cosa Abbiamo
- Struttura CMS con blocchi configurabili (home.json)
- Hero component (hero-betting.blade.php)
- Trust bar component (trust-bar.blade.php)
- Filament PredictTableWidget per lista mercati
- Layout base con dark mode support

### ❌ Cosa Manca (Gap Analysis)

#### 1. Performance (Core Web Vitals)
- [ ] LCP ottimizzato (hero image pesante)
- [ ] INP < 200ms (JavaScript non ottimizzato)
- [ ] CLS < 0.1 (immagini senza dimensioni riservate)
- [ ] Lazy loading per immagini below-fold
- [ ] Critical CSS inline

#### 2. Accessibilità WCAG 2.2 AA
- [ ] Skip navigation link
- [ ] Focus indicators visibili su tutti gli elementi interattivi
- [ ] Target touch ≥ 44x44px
- [ ] ARIA labels per screen reader
- [ ] Contrasto colori ≥ 4.5:1 (verificare)
- [ ] Navigazione completa da tastiera
- [ ] Dichiarazione accessibilità

#### 3. Mobile First
- [ ] Touch targets 44x44px verificati
- [ ] Hamburger menu ottimizzato
- [ ] Sticky CTA per mobile
- [ ] Swipe gestures per caroselli
- [ ] Font size ≥ 16px

#### 4. Micro-Interazioni (6 tipi)
- [ ] Hover effects su tutti i bottoni
- [ ] Form feedback per newsletter/signup
- [ ] Transizioni fluide tra sezioni
- [ ] Loading states (skeleton screens)
- [ ] Counters animati (volume, utenti)
- [ ] Scroll effects (fade-in progressivo)

#### 5. SEO
- [ ] Meta title/description ottimizzati
- [ ] Struttura H1-H6 gerarchica
- [ ] JSON-LD structured data (Organization, WebSite)
- [ ] Open Graph tags
- [ ] Twitter Card
- [ ] Canonical URL
- [ ] Alt text descrittivo per immagini

#### 6. Design Emozionale
- [ ] First impression ottimizzata (15 secondi)
- [ ] Psicologia del colore applicata
- [ ] Social proof reale (testimonianze, numeri)
- [ ] Storytelling visivo (timeline, journey)
- [ ] Flow state (navigazione fluida)

#### 7. Web Design Cinetico
- [ ] Animazioni CSS per hero elements
- [ ] Transizioni fluide su odds update
- [ ] Parallax scrolling (opzionale)
- [ ] Stagger entrance per cards
- [ ] Reduced motion support

---

## 🎯 Sprint di Implementazione

### Sprint 1 — Critico (2026-03-18 → 2026-03-20)
**Priorità**: Accessibilità, Performance, SEO base

1. [ ] **Skip Navigation** — Aggiungere link per saltare al contenuto
2. [ ] **Focus Indicators** — Implementare su tutti gli elementi interattivi
3. [ ] **Touch Targets** — Verificare 44x44px su tutti i bottoni
4. [ ] **Meta Tags** — Title, description, Open Graph, Twitter Card
5. [ ] **JSON-LD** — Structured data per Organization e WebSite
6. [ ] **Lazy Loading** — Immagini below-fold
7. [ ] **Critical CSS** — Inline above-the-fold styles

### Sprint 2 — Alta (2026-03-20 → 2026-03-22)
**Priorità**: Micro-Interazioni, Mobile First

1. [ ] **Hover Effects** — Tutti i bottoni e cards
2. [ ] **Loading States** — Skeleton screens per mercati
3. [ ] **Counters Animati** — Volume, utenti, mercati attivi
4. [ ] **Scroll Effects** — Fade-in progressivo sezioni
5. [ ] **Sticky CTA Mobile** — Place bet sempre visibile
6. [ ] **Hamburger Menu** — Ottimizzato per mobile

### Sprint 3 — Media (2026-03-22 → 2026-03-25)
**Priorità**: Design Emozionale, Web Design Cinetico

1. [ ] **Hero Optimization** — First impression < 15 secondi
2. [ ] **Social Proof** — Testimonianze reali, numeri aggiornati
3. [ ] **Color Psychology** — CTA con colori strategici
4. [ ] **Storytelling** — Timeline journey utente
5. [ ] **Kinetic Animations** — GSAP per timeline complesse
6. [ ] **Parallax** — Effetti profondità (opzionale)

### Sprint 4 — Verifica (2026-03-25 → 2026-03-28)
**Priorità**: Quality Gate, Testing

1. [ ] **Lighthouse Audit** — Score > 90/100
2. [ ] **WAVE Accessibility** — Zero errori
3. [ ] **Core Web Vitals** — LCP < 2.5s, INP < 200ms, CLS < 0.1
4. [ ] **Keyboard Navigation** — Test completo
5. [ ] **Screen Reader Test** — NVDA/JAWS
6. [ ] **Mobile Testing** — Device reali

---

## 📝 Documentazione da Aggiornare

### Ad Ogni Modifica
- [ ] `Themes/TwentyOne/docs/HOMEPAGE_IMPROVEMENTS.md` — Log modifiche
- [ ] `docs/project/website-checklist.md` — Spuntare voci implementate
- [ ] `.github/ISSUES/044-website-checklist.md` — Aggiornare stato
- [ ] `.github/DISCUSSIONS/004-website-design-strategy.md` — Condividere progressi

### Creare Nuovi File
- [ ] `Themes/TwentyOne/docs/HOMEPAGE_SEO.md` — SEO implementation
- [ ] `Themes/TwentyOne/docs/HOMEPAGE_ACCESSIBILITY.md` — WCAG compliance
- [ ] `Themes/TwentyOne/docs/HOMEPAGE_PERFORMANCE.md` — Core Web Vitals
- [ ] `Modules/Predict/docs/HOMEPAGE_COMPONENTS.md` — Componenti creati

---

## 🔄 Workflow Multi-Agente

**Per altri agenti AI**:
1. Leggere questo file PRIMA di iniziare
2. Controllare la tabella Sprint — se una voce è già ✅, passare oltre
3. Aggiornare questo file con le proprie azioni completate
4. Coordinarsi via GitHub Issues/Discussions
5. NON duplicare lavoro — migliorare, non riscrivere

---

## 📊 Metriche di Successo

### Target Homepage
| Metrica | Attuale | Target | Data Target |
|---------|---------|--------|-------------|
| **Lighthouse Performance** | Da misurare | > 90 | 2026-03-28 |
| **Lighthouse Accessibility** | Da misurare | > 90 | 2026-03-28 |
| **Lighthouse SEO** | Da misurare | > 95 | 2026-03-28 |
| **LCP** | Da misurare | < 2.5s | 2026-03-28 |
| **INP** | Da misurare | < 200ms | 2026-03-28 |
| **CLS** | Da misurare | < 0.1 | 2026-03-28 |
| **Bounce Rate** | Da misurare | < 40% | 2026-04-01 |
| **Session Duration** | Da misurare | > 2min | 2026-04-01 |

---

**Ultimo aggiornamento**: 2026-03-18  
**Prossima revisione**: 2026-03-20  
**Responsabile**: AI Agents Team  
**Stato**: 🔄 IN IMPLEMENTAZIONE
