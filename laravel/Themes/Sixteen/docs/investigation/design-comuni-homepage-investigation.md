---
id: "kp-design-comuni-homepage-investigation"
type: "investigation"
title: "Investigation — Design Comuni Homepage Template Analysis for Sixteen Theme"
description: "Analisi del template HTML della homepage di Design Comuni per identificare pattern di design, componenti universali e struttura dati che possano essere applicati alla homepage di Sixteen."
frontmatter:
  github_issue: "#54"
links:
  github_issue: "#54"
status: draft
updated: 2026-10-11
tags: [docs, sixteen, investigation]
qmd: "design comuni homepage investigation sixteen"
---
## Contesto

Il tema Sixteen è attualmente in fase di migrazione verso una struttura dati guidata da JSON (vedi `home.json` e `investigation-sixteen-json-alignment.md`). La homepage attuale presenta errori di rendering (icone mancanti, view non trovate) e necessita di un miglioramento visivo e funzionale ispirandosi al design system ufficiale Design Comuni per i comuni italiani.

## Obiettivo dell'Investigazione

Analizzare il template HTML della homepage di Design Comuni (`src/pages/index.hbs`) per:
1. Identificare componenti universali riutilizzabili
2. Analizzare la struttura HTML e le classi Tailwind CSS utilizzate
3. Capire il pattern di layout (grid, spacing, tipografia)
4. Identificare i componenti specifici per le sezioni (hero, cta, testimonials)
5. Estrarre i pattern di interazione (scroll, trigger, transizioni)
6. Identificare le differenze con la struttura attuale di Sixteen

## Struttura Analizzata

### Sezione 1: Hero (Homepage Hero Section)
- Utilizza un layout a due colonne (testo a sinistra, immagine/mappa a destra)
- Hero con titolo grande (h1) e sottotitolo
- CTA primarie e secondarie con classi specifiche per stile e interazione
- Utilizzo di `container` per centrare il contenuto
- Utilizzo di `row` e `col-12` per layout responsive
- Classe `it-top-navscroll` per la barra di navigazione fissa

### Sezione 2: Features (Feature Grid)
- Layout a griglia 2 colonne su desktop, 1 colonna su mobile
- Ogni card ha icona, titolo, descrizione
- Spaziatura coerente tra elementi (padding, margin)
- Utilizzo di `bg-gray-100` per sfondo delle card
- Icone da `bootstrap-italia` (classi come `icon-map-pin`)

### Sezione 3: Stats (Contatori)
- Layout a griglia 2x2 (4 sezioni)
- Contatori con valore numerico grande, label descrittivo, colore di sfondo
- Utilizzo di `bg-orange-50`, `bg-yellow-50`, ecc. per differenziare le categorie
- Icone piccole accanto ai valori

### Sezione 4: CTA Finale (Final CTA)
- Sezione dedicata con titolo, descrizione, e bottone primario
- Bottone con classe specifica per azione (es. `btn-primary`)
- Spaziatura generosa intorno alla sezione

### Sezione 5: Testimonials (Recensioni)
- Layout a griglia 3 colonne
- Ogni testimonial ha foto, nome, città, testo, valutazione
- Utilizzo di `blockquote` o componenti simili per stile
- Colori caldi per le recensioni positive

## Pattern di Design Identificati

### 1. Grid System
- Utilizzo di `grid-cols-1` (mobile) → `grid-cols-2` (tablet) → `grid-cols-3` (desktop)
- Spaziatura uniforme con `gap-6` tra elementi
- Padding laterale `px-4` su desktop, `px-6` su mobile

### 2. Tipografia
- Titolo principale: `text-4xl md:text-5xl` (grande, leggibile)
- Testo secondario: `text-lg` o `text-base`
- Line-height: `leading-relaxed` per leggibilità
- Spaziatura tra elementi: `py-8` per sezioni grandi, `py-6` per sezioni medie

### 2.1 Pattern della Hero Section
- Hero con `min-h-[60vh]` per altezza minima
- Testo centrato verticalmente con `flex flex-col items-center justify-center`
- CTA primarie con `bg-blue-600` e `text-white` (contrasto alto)
- CTA secondarie con `bg-transparent` e `border` (non riempite)

### Sezione 6: Componenti Universali Identificati
1. `cmp-hero`: Componente per hero section con titolo, descrizione, due CTA
2. `cmp-feature-card`: Card con icona, titolo, descrizione, background neutro
3. `cmp-stat-card`: Card per contatori con valore grande, label, colore di sfondo
4. `cmp-cta-section`: Sezione con titolo, descrizione e bottone CTA
5. `cmp-testimonial`: Componente per testimonial con foto, testo, valutazione

## Differenze con Sixteen Attuale

1. **Layout della Hero**: 
   - Sixteen: Layout a due colonne (testo + mappa)
   - Design Comuni: Layout a due colonne (testo + immagine) - simile ma con differenze nei padding e nella tipografia

2. **Componenti**:
   - Sixteen: Componenti personalizzati (es. `cmp-hero`) non riutilizzabili
   - Design Comuni: Componenti universali (`cmp-hero`, `cmp-feature-card`) riutilizzabili in più pagine

### 3. Pattern da Adottare
- Usare componenti universali invece di componenti specifici per pagina
- Applicare pattern di layout coerenti (grid, padding, spacing)
- Utilizzare classi Tailwind coerenti con il design system (non personalizzazioni ad-hoc)
- Implementare pattern di interazione (es. fade-in, scroll-triggered animations)

## Raccomandazioni per Sixteen

1. **Ristrutturare la Homepage**:
   - Utilizzare layout a due colonne per hero (testo + immagine)
   - Utilizzare componenti universali (`cmp-hero`, `cmp-feature-card`, `cmp-stat-card`)
   - Applicare griglia 2-3 colonne per features
   - Utilizzare colori di sfondo per sezioni (es. `bg-orange-50` per stats)

2. **Migliorare l'Interazione**:
   - Aggiungere transizioni fluide (fade-in, slide-up)
   - Implementare ScrollTrigger per animazioni a scorrimento
   - Garantire preferiti-reduced-motion per accessibilità

3. **Aggiornare il Template Blade**:
   - Sostituire `resources/views/pages/index.blade.php` con una versione che utilizzi i componenti universali
   - Mantenere la struttura JSON-driven (`home.json`) come fonte dati

## Conclusioni

Il design system Design Comuni offre un modello solido per la homepage di Sixteen. I pattern identificati (grid system, componenti universali, tipografia coerente, accessibilità) possono essere applicati direttamente al tema Sixteen senza richiedere modifiche strutturali al sistema JSON-driven già implementato. La chiave è utilizzare componenti universali e mantenere la struttura dati coerente con `home.json`.