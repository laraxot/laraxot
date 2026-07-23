# Audit `/it` — WCAG, SEO, Rich Snippet, Visual Parity (2026-05-29)

**URL:** http://127.0.0.1:8000/it
**Reference:** https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html
**Tool:** pa11y 9.1.1 (WCAG2AA), HTML structure diff, visual comparison

---

## WCAG2AA (pa11y) — Issues Reali

### CRITICAL (bloccante)

| # | Codice | Elemento | Issue | Fix |
|---|--------|----------|-------|-----|
| W1 | H25.1.NoTitleEl | `<head>` | **Nessun `<title>`** — documento senza title | Aggiungere `<title>Elenco segnalazioni - Nome del Comune</title>` nel layout |
| W2 | G18.Fail | `span "Segnala un disservizio"` | **Contrasto insufficiente**: 3.24:1 (min 4.5:1) su bottone CTA | Fix colore testo / sfondo nel tema |

### Da verificare (link context, map tiles, autocomplete)

| # | Codice | Issue | Note |
|---|--------|-------|------|
| W3 | H77,H78,H79 | Link context — molti link senza testo descrittivo sufficiente | Social icons (`#` target), skip link (già ok: "Vai ai contenuti"), nav links ok |
| W4 | G94.Image | Mappa Leaflet: OSM tile `<img alt="">` senza descrizione | Le tile OSM sono decorative — `alt=""` è semanticamente corretto, ma pa11y segnala |
| W5 | G73,G74 | Tile map (continuazione) | Falsi positivi per tile OSM |
| W6 | H98 | Campi rating radio senza autocomplete attribute | Aggiungere `autocomplete="off"` o appropriato |
| W7 | H98 | Campo text feedback senza autocomplete | Aggiungere attributo appropriato |
| W8 | H91.Select.Name | Select hidden (debugbar) | Falso positivo per debugbar |
| W9 | F68 | Form field senza label (debugbar) | Falso positivo per debugbar |
| W10 | G107 | Change of context su focus (molti button) | Principalmente modal toggle, nav toggle — pattern Bootstrap Italia standard |

### Falsi Positivi da escludere
- Debugbar (phpdebugbar): ~220 issues — irrilevanti per il sito pubblico
- OSM tile images: alt="" è corretto per decorative images
- Leaflet attribution link
- `select hidden` debugbar

### Riepilogo WCAG
- **W1 (manca `<title>`)** — bloccante, fix immediato
- **W2 (contrasto CTA)** — bloccante, fix CSS tema
- **W3-W10** — non blocking, da migliorare ma non prioritario

---

## SEO Audit

| # | Elemento | Stato | Impatto |
|---|----------|-------|---------|
| S1 | `<title>` | **ASSENTE** | CRITICO — Google richiede title per ranking |
| S2 | `<meta name="description">` | **ASSENTE** | ALTO — Google usa description nei snippet |
| S3 | `<link rel="canonical">` | **ASSENTE** | MEDIO — rischio contenuto duplicato |
| S4 | Open Graph (`og:title`, `og:description`, `og:image`) | **ASSENTI** | MEDIO — condivisione social senza preview |
| S5 | Twitter Card | **ASSENTI** | BASSO |
| S6 | `h1` presente | ✅ "Segnalazioni" | OK ma testo diverso da reference ("Elenco segnalazioni") |
| S7 | `lang="it"` | ✅ | OK |
| S8 | Viewport meta | ✅ | OK |
| S9 | Heading hierarchy | ✅ h1→h2→h3→h4 corretta | OK |

**Fix SEO prioritario:**
```html
<title>Elenco segnalazioni - Nome del Comune</title>
<meta name="description" content="Consulta l'elenco delle segnalazioni aperte nel territorio. Filtra per categoria e visualizza su mappa le segnalazioni dei cittadini.">
<link rel="canonical" href="http://127.0.0.1:8000/it">
```

---

## Rich Snippets / Structured Data

| # | Tipo | Stato |
|---|------|-------|
| R1 | JSON-LD `BreadcrumbList` | **ASSENTE** |
| R2 | JSON-LD `WebPage` / `LocalBusiness` | **ASSENTE** |
| R3 | `itemprop` / `itemscope` | **ASSENTI** |
| R4 | `data-element` AGID | ✅ **PRESENTI** (26 elementi) |
| R5 | Reference `data-element="contacts"` | **ASSENTE** in locale |
| R6 | Reference `data-element="appointment-booking"` | **ASSENTE** in locale |

**Note:** Il reference Design Comuni non ha JSON-LD — i `data-element` AGID sostituiscono. Mancano `contacts` e `appointment-booking`.

---

## Visual Parity — Gap Analysis

### Struttura Layout

| # | Elemento | Reference | Locale `/it` | Gap |
|---|----------|-----------|--------------|-----|
| V1 | **Sidebar filtri** | `col-lg-3 d-none d-lg-block` con 11 checkbox + conteggi | **ASSENTE** (layout 1 colonna) | CRITICO — D1 |
| V2 | **Colonne** | `col-lg-3` + `col-lg-8 offset-lg-1` | `col-12 col-lg-10 offset-lg-1` | Le due colonne non esistono |
| V3 | **Breadcrumb label** | "Home / Elenco segnalazioni" | "Home / Segnalazioni" | D2/D3 |
| V4 | **H1** | "Elenco segnalazioni" | "Segnalazioni" | D2 |
| V5 | **Sottotitolo** | "Negli ultimi 12 mesi sono state risolte 73 segnalazioni." | "Consulta le segnalazioni aperte nel territorio e filtra i risultati per categoria." | D4 |
| V6 | **Risultati** | "645 Risultati" | "0 segnalazioni trovate" | D5 |
| V7 | **"Rimuovi tutti i filtri"** | Link testuale a destra | Bottone verde pieno (diverso stile) | D6 |
| V8 | **Tabs** | `nav.nav-tabs` + `nav-link` | Same classi (Bootstrap Italia) | ✅ |
| V9 | **Mappa** | Placeholder img + pin | Leaflet (centrata su Europa, 0 marker) | D7 |
| V10 | **CTA heading** | "Fai una segnalazione" | "Hai notato un disservizio?" | D9 |
| V11 | **CTA copy** | "autenticato con SPID o CIE" | "Invia una nuova segnalazione e aiuta il Comune a intervenire in modo più rapido." | D9 |
| V12 | **CTA bottone** | "Segnala disservizio" | "Segnala un disservizio" | D9 |
| V13 | **Rating** | Box pulito, stelle outline, testo completo | **Layout rotto**: stelle gialle piene sovrapposte a testo tagliato | D8 |
| V14 | **Contatti heading** | "Contatta il comune" | "Hai bisogno di aiuto?" | D10 |
| V15 | **Contatti items** | 4 voci: FAQ, Assistenza, Numero verde, Appuntamento | 1 voce (FAQ) | D10 |
| V16 | **Footer** | EU funding "Finanziato UE NextGenerationEU" + logo | ✅ **PRESENTE** | D11 risolto |

### Dettaglio Gap Bloccanti (P0 per STORY-062/058)

**V1/V2 — Sidebar + colonna layout:**
- Radice: `layout.blade.php:160` — `@if (!empty($filters['items']))` nasconde l'`aside.col-lg-3` se items vuoto
- Fix: sempre rendere la colonna anche se items vuoto, con placeholder "Nessun filtro disponibile"
- Layout cambia da `col-12 col-lg-10 offset-lg-1` a `col-lg-3` + `col-lg-8 offset-lg-1`

**V13 — Rating rotto:**
- Stelle gialle piene sovrapposte a testo: overflow/layout CSS bug nel blocco `feedback.rating`
- Fix: `cmp-rating` deve avere `.card-wrapper` con padding corretto, stelle in outline

### Elementi `data-element` Mancanti in Locale
```
Reference:       Locale:
data-element="contacts"          → ASSENTE
data-element="appointment-booking" → ASSENTE
```

---

## Raccomandazioni Prioritizzate

### P0 — Fix immediato (blocking)
1. **W1:** Aggiungere `<title>` al layout
2. **V1/V2:** Sidebar filtri `col-lg-3` sempre presente + griglia a 2 colonne
3. **V13:** Fix layout rating (stelle gialle sovrapposte)

### P1 — Sprint 6
4. **S1/S2:** Aggiungere meta title + description
5. **V3/V4:** Allineare breadcrumb e H1 a "Elenco segnalazioni"
6. **V5:** Sottotitolo con statistica dinamica
7. **V10/V11/V12:** Allineare CTA copy a reference (SPID/CIE)

### P2 — Sprint 7+
8. **V14/V15:** Contatti con 4 voci + `data-element="contacts"`
9. **V7:** "Rimuovi filtri" come link non bottone
10. **S3:** Canonical URL
11. **V9:** Mappa centrata su comune con zoom default

---

## Tools Installati

| Tool | Versione | Uso |
|------|----------|-----|
| pa11y | 9.1.1 | WCAG2AA audit automation |
| Lighthouse | (non disponibile senza Chrome) | Per SEO/Accessibility score numerico |
| `curl` | system | HTML fetch per diff strutturale |

**Nota:** Lighthouse non eseguibile in questo ambiente (no Chrome binary). Consigliata esecuzione locale via Chrome DevTools o `npx lighthouse` su workstation con Chrome.

---

*Audit eseguito: 2026-05-29 — Agente AI: OpenCode (deepseek-v4-flash-free)*
