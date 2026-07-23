# Piano di Lavoro: Homepage HTML Parity → CSS/JS Alignment

**Data**: 2026-04-02
**Stato**: 🟡 In Pianificazione
**Priorità**: 🔴 CRITICA
**Agente Responsabile**: Cascade AI
**Condiviso con**: Tutti gli agenti AI del progetto

---

## 🎯 Obiettivo

Rendere la pagina `http://127.0.0.1:8000/it/tests/homepage` **visivamente identica** a
`https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html`

**Approccio**: Tailwind CSS + Alpine.js (NO Bootstrap Italia runtime)

---

## 📊 Stato Attuale

### HTML Structure
| Metrica | Valore | Note |
|---------|--------|------|
| Match strutturale | 53% | Wrapper div extra dai CMS blocks |
| Sezioni principali | ✅ Tutte presenti | header, main, 4 sections, footer |
| Calendario | ✅ 7 slide, stessa struttura | Splide carousel |
| Path asset | ✅ Corretti | `/themes/Sixteen/design-comuni/assets/` |

### Differenze da Risolvere
1. **Extra section** `<section class="section section-muted">` tra useful-links e rating
2. **Wrapper div aggiuntivi** nei CMS blocks (non critico per rendering)
3. **CSS mancanti/incompleti** per replicare Bootstrap Italia
4. **JS mancanti** per carousel, menu mobile, modali, rating

---

## 📋 Piano di Lavoro

### Fase 1: Analisi e Screenshots (30 min)
- [ ] Catturare screenshot reference homepage
- [ ] Catturare screenshot FixCity homepage
- [ ] Identificare differenze visive specifiche
- [ ] Documentare in `docs/design-comuni/screenshots/`

### Fase 2: CSS - Tailwind Replication (2-3 ore)
- [ ] **Header**: Slim wrapper, brand, socials, navbar, hamburger menu
- [ ] **Hero Section**: Card con immagine, category-top, chip, read-more
- [ ] **Calendario Section**: Card overlapping, teaser cards, carousel splide
- [ ] **Evidence Section**: Background image, card-teaser, link-list, chips
- [ ] **Useful Links Section**: Search input, link-list
- [ ] **Rating Section**: Star rating, feedback form
- [ ] **Footer**: 4 colonne, social links, back-to-top

### Fase 3: JS - Alpine.js Behavior (1-2 ore)
- [ ] **Navbar**: Toggle hamburger, megamenu, overlay
- [ ] **Carousel**: Splide initialization, navigation arrows
- [ ] **Rating**: Star selection, form steps, feedback
- [ ] **Search**: Modal toggle, autocomplete
- [ ] **Language selector**: Dropdown toggle
- [ ] **Back to top**: Scroll behavior

### Fase 4: Testing e Verifica (30 min)
- [ ] Build: `npm run build` + `npm run copy`
- [ ] Screenshot comparison
- [ ] Mobile responsive check
- [ ] Dark mode check (se applicabile)

---

## 🏗️ Architettura CSS

### File Principali
```
Themes/Sixteen/resources/css/
├── app.css                 # Entry point Vite
├── design-comuni.css       # Stili specifici design-comuni
├── bootstrap-italia.css    # Replicazione classi Bootstrap Italia
├── agid.css                # Override AGID-specifici
└── components/             # Componenti modulari
    ├── header.css
    ├── cards.css
    ├── carousel.css
    ├── footer.css
    └── rating.css
```

### Strategia Tailwind
- Usare `@apply` per replicare classi Bootstrap Italia
- Mantenere classi originali come hook (es. `it-header-wrapper`)
- Creare utility custom per pattern ricorrenti

---

## 🏗️ Architettura JS

### File Principali
```
Themes/Sixteen/resources/js/
├── app.js                  # Entry point Vite
├── custom.js               # Comportamenti custom
├── components/
│   ├── header.js           # Navbar toggle, megamenu
│   ├── carousel.js         # Splide initialization
│   ├── rating.js           # Star rating behavior
│   └── search.js           # Search modal, autocomplete
└── swiper.js               # Già presente per carousel
```

### Strategia Alpine.js
- Usare `x-data` per stato locale
- `x-show` per toggle visibility
- `x-transition` per animazioni
- Integrare con Splide per carousel

---

## 🤝 Coordinamento con Altri Agenti

### Agenti che Potrebbero Essere Influenzati
| Agente | Area | Note |
|--------|------|------|
| Agente CMS | Blocks JSON | Non modificare struttura JSON |
| Agente Theme | Layout files | Coordinare su `layouts.app` |
| Agente Folio | Routes | Non modificare routing |

### Regole di Coordinamento
1. **NON modificare** file blade senza consultare
2. **NON modificare** JSON content senza consultare
3. **SOLO CSS/JS** in `Themes/Sixteen/resources/`
4. **Documentare** ogni modifica in `docs/design-comuni/`

### Comunicazione
- Creare GitHub Issue: `[CSS/JS] Homepage Visual Alignment`
- Aggiornare issue con progresso
- Commentare blockers per altri agenti

---

## 📁 File da Modificare

### CSS
- `Themes/Sixteen/resources/css/design-comuni.css` - Stili principali
- `Themes/Sixteen/resources/css/bootstrap-italia.css` - Classi replicate
- `Themes/Sixteen/resources/css/components/*.css` - Componenti

### JS
- `Themes/Sixteen/resources/js/custom.js` - Comportamenti
- `Themes/Sixteen/resources/js/components/*.js` - Moduli

### Build
- `Themes/Sixteen/vite.config.js` - Se necessario

---

## ⚡ Comandi Utili

```bash
# Build CSS/JS
cd laravel/Themes/Sixteen
npm run build
npm run copy

# Dev mode (hot reload)
npm run dev

# Verifica file copiati
ls -la public_html/themes/Sixteen/build/
```

---

## 📊 Metriche di Successo

| Metrica | Target | Attuale |
|---------|--------|---------|
| Visual match | 95%+ | Da misurare |
| CSS classes replicate | 100% | In progresso |
| JS behaviors | 100% | In progresso |
| Mobile responsive | ✅ | Da verificare |
| Build senza errori | ✅ | ✅ |

---

## 📝 Note Importanti

1. **NO Bootstrap Italia**: Replichiamo con Tailwind, non includiamo la libreria
2. **Mantieni classi originali**: Le classi `it-*` restano come hook
3. **Documenta tutto**: Ogni modifica va documentata
4. **Testa su browser**: Non fidarti solo del build
5. **Mobile first**: Responsive è prioritario

---

**Collegamenti**:
- [00-index.md](00-index.md) - Indice documentazione
- [html-structure-comparison-2026-04-02.md](html-structure-comparison-2026-04-02.md) - Analisi struttura
- [GSD_HTML_PARITY_PLAN.md](GSD_HTML_PARITY_PLAN.md) - Piano originale
- [../../../docs/design-comuni/html-match.md](../../../docs/design-comuni/html-match.md) - Regola globale

---

**Ultimo aggiornamento**: 2026-04-02 11:15
**Prossimo aggiornamento**: Dopo Fase 1 (screenshots)
