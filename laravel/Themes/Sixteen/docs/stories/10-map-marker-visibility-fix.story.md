---
id: "sixteen-fo-10-map-marker-visibility-fix"
type: "story"
title: "Map marker visibility fix for ticket create flow (DesignComuni compliant)"
description: "Migliorare la visibilità del marker sulla mappa nel wizard di creazione ticket (step 'form.dati-di-segnalazione::data::wizard-step') seguendo i pattern DesignComuni, usando Tailwind @apply + Alpine.js + Lit, garantendo WCAG 2.1 AA."
frontmatter:
  github_issue: "#64"
  github_discussion: "#64"
links:
  github_issue: "#64"
  github_discussion: "#64"
  design_ref: "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html"
  investigation_ref: "docs/investigation/homepage-design-comuni-investigation.md"
  marker_config: "Modules/Geo/resources/js/components/map/marker-config.js"
---

# Map Marker Visibility Fix — Story

## Goal
Migliorare la visibilità del marker sulla mappa nel flusso di creazione ticket (step 2/4 "Dati segnalazione") rendendolo chiaramente visibile su tutti i layer di mappa (street, satellite, hybrid) e in entrambe le modalità (light/dark), seguendo i principi DesignComuni per accessibilità e UX.

## Background
- **Posizione attuale**: `http://localhost:8000/it/tickets/create?step=form.dati-di-segnalazione::data::wizard-step`
- **Componente**: `CoordinatePicker` (Filament) → `coordinate-picker-lit` (Lit web component) → Leaflet `L.divIcon` custom marker
- **File chiave**: `Modules/Geo/resources/js/components/map/marker-config.js` (funzione `createGeoMapLeafletIcon` + CSS `markerCardStylesText`)
- **Problema**: Marker "poco visibile" — contrasto insufficiente su layer chiari/scuri, dimensione ridotta, mancanza di stati focus/hover accessibili

## Analisi DesignComuni (segnalazione-02-dati.html)
DesignComuni per la pagina "Dati segnalazione" usa:
- **Marker evidente**: icona "segnalatore" (pin) bianca su cerchio colorato primario (#0057b8) con bordo bianco spesso (3px)
- **Dimensioni**: ~44px diametro (più grande del corrente 40px)
- **Ombra**: `drop-shadow(0 2px 4px rgba(0,0,0,0.3))` per staccarsi dallo sfondo mappa
- **Stato focus**: outline visibile `0 0 0 3px #fff, 0 0 0 5px #0057b8` (WCAG 2.1 AA)
- **Animazione**: pulse sottile su hover (rispetta `prefers-reduced-motion`)
- **Accessibilità**: `aria-label` descrittivo, ruolo `img` o `button` se draggable

## Acceptance Criteria

### Marker Base (DesignComuni compliant)
- [ ] **Dimensione**: 44px × 44px (era 40px) — `MARKER_WIDTH = 44`, `MARKER_BODY_SIZE = 44`
- [ ] **Forma**: Cerchio (era quadrato arrotondato 12%) — `border-radius: 50%`
- [ ] **Colori DesignComuni**:
  - Primary: `#0057b8` (blu istituzionale)
  - Success: `#28a745` (verde per "confermato")
  - Warning: `#ffc107` (giallo "in lavorazione")
  - Danger: `#dc3545` (rosso "respinto")
  - Default: `#0057b8`
- [ ] **Bordo**: 3px solid `#fff` (era 2.5px) — per contrasto su mappe chiare
- [ ] **Icona**: SVG `it-map-pin` da sprite DesignComuni (`/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-map-pin`) — bianco, 22px
- [ ] **Ombra**: `filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3)) drop-shadow(0 4px 8px rgba(0,0,0,0.15))`
- [ ] **Glow**: rimuovere glow colorato (non DesignComuni), usare solo ombra neutra

### Stati Interattivi (WCAG 2.1 AA)
- [ ] **Hover**: `transform: scale(1.1)` + `filter: drop-shadow(0 4px 8px rgba(0,0,0,0.35))` — rispetta `prefers-reduced-motion: reduce` (disabilita transform)
- [ ] **Focus-visible**: `outline: none; box-shadow: 0 0 0 3px #fff, 0 0 0 5px #0057b8` — visibile su tutti gli sfondi
- [ ] **Dragging**: `cursor: grabbing` + `z-index: 1000` + ombra accentuata
- [ ] **Attivo (selezionato)**: anello pulsante `box-shadow: 0 0 0 3px #fff, 0 0 0 6px #0057b8` + animazione pulse (rispetta `prefers-reduced-motion`)

### Accessibilità
- [ ] **ARIA**: `role="img" aria-label="Posizione segnalazione: {indirizzo o coordinate}"` su wrapper marker
- [ ] **Tooltip**: nativo Leaflet `bindTooltip` con indirizzo (se disponibile) — accessibile da tastiera
- [ ] **Contrasto**: icona bianca su sfondo primario = 7.8:1 (passa AAA), bordo bianco su mappa = garantito

### Dark Mode
- [ ] **Colori invariati** (DesignComuni non cambia primary per dark mode)
- [ ] **Ombra**: `drop-shadow(0 2px 4px rgba(0,0,0,0.5))` più forte per sfondi scuri
- [ ] **Focus**: `box-shadow: 0 0 0 3px #fff, 0 0 0 5px #4d9fff` (primary lightened per dark)

### Responsive / Map Layer
- [ ] **Street layer**: visibile chiaramente
- [ ] **Satellite layer**: bordo bianco + ombra garantiscono visibilità
- [ ] **Hybrid layer**: funziona su entrambi
- [ ] **Zoom < 10**: marker ridotto a 32px (CSS `@media` o JS resize handler)
- [ ] **Zoom > 16**: marker 48px per precisione

## Implementazione Tecnica

### 1. Aggiorna `marker-config.js`
```javascript
// Nuove costanti DesignComuni
const MARKER_WIDTH = 44;
const MARKER_BODY_SIZE = 44;
const MARKER_GLYPH_SIZE = 22;
const MARKER_BORDER_WIDTH = 3;

// Icona da sprite DesignComuni
const DESIGN_COMUNI_SPRITE = '/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-map-pin';

export function createGeoMapLeafletIcon(L, color = '#0057b8', iconUrl = null, typeLabel = '') {
    const statusColor = normalizeGeoMapColor(color, '#0057b8');
    // Icona SVG da sprite (bianca per contrasto)
    const glyph = `<svg class="geo-map-marker-glyph" aria-hidden="true" width="${MARKER_GLYPH_SIZE}" height="${MARKER_GLYPH_SIZE}"><use href="${DESIGN_COMUNI_SPRITE}"></use></svg>`;
    
    return L.divIcon({
        html: `<div class="geo-map-marker-designcomuni" style="--marker-color:${statusColor}" aria-hidden="true" role="img" aria-label="Marker posizione segnalazione">
            <div class="geo-map-marker-designcomuni__circle">
                <div class="geo-map-marker-designcomuni__icon">${glyph}</div>
            </div>
        </div>`,
        className: 'geo-map-marker-wrapper geo-map-marker-wrapper--designcomuni',
        iconSize: [MARKER_WIDTH, MARKER_BODY_SIZE],
        iconAnchor: [MARKER_WIDTH / 2, MARKER_BODY_SIZE / 2],
        popupAnchor: [0, -MARKER_BODY_SIZE / 2],
    });
}
```

### 2. Nuovo CSS `markerCardStylesText` (DesignComuni)
```css
.geo-map-marker-wrapper--designcomuni {
    background: transparent !important;
    border: none !important;
}
.geo-map-marker-designcomuni {
    position: relative;
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3)) drop-shadow(0 4px 8px rgba(0,0,0,0.15));
    transition: transform 0.15s ease, filter 0.15s ease, box-shadow 0.15s ease;
}
@media (prefers-reduced-motion: reduce) {
    .geo-map-marker-designcomuni { transition: none !important; }
}
.geo-map-marker-designcomuni__circle {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: var(--marker-color, #0057b8);
    border: 3px solid #fff;
    box-shadow: 
        0 0 0 1px color-mix(in srgb, var(--marker-color, #0057b8) 30%, transparent),
        inset 0 1px 0 rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: box-shadow 0.15s ease, filter 0.15s ease;
}
.geo-map-marker-designcomuni__icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    color: #fff;
    filter: brightness(0) invert(1); /* forza bianco */
}
.geo-map-marker-designcomuni__icon svg {
    width: 100%;
    height: 100%;
}

/* Hover */
.leaflet-marker-icon.geo-map-marker-wrapper--designcomuni:hover .geo-map-marker-designcomuni {
    transform: scale(1.1);
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.35)) drop-shadow(0 8px 16px rgba(0,0,0,0.2));
}
@media (prefers-reduced-motion: reduce) {
    .leaflet-marker-icon.geo-map-marker-wrapper--designcomuni:hover .geo-map-marker-designcomuni {
        transform: none;
    }
}

/* Focus-visible (WCAG 2.1 AA) */
.leaflet-marker-icon.geo-map-marker-wrapper--designcomuni:focus-visible {
    outline: none;
}
.leaflet-marker-icon.geo-map-marker-wrapper--designcomuni:focus-visible .geo-map-marker-designcomuni__circle {
    box-shadow: 
        0 0 0 3px #fff,
        0 0 0 5px var(--marker-color, #0057b8),
        0 0 12px rgba(0,0,0,0.2),
        inset 0 1px 0 rgba(255,255,255,0.3);
}

/* Dragging */
.leaflet-marker-icon.geo-map-marker-wrapper--designcomuni.leaflet-marker-draggable.dragging .geo-map-marker-designcomuni {
    z-index: 1000;
    filter: drop-shadow(0 6px 12px rgba(0,0,0,0.4)) drop-shadow(0 12px 24px rgba(0,0,0,0.25));
}

/* Active/Selected pulse */
.geo-map-marker-designcomuni--active .geo-map-marker-designcomuni__circle {
    animation: marker-pulse 2s ease-in-out infinite;
    box-shadow: 
        0 0 0 3px #fff,
        0 0 0 6px var(--marker-color, #0057b8);
}
@keyframes marker-pulse {
    0%, 100% { box-shadow: 0 0 0 3px #fff, 0 0 0 6px var(--marker-color); }
    50% { box-shadow: 0 0 0 3px #fff, 0 0 0 10px color-mix(in srgb, var(--marker-color) 40%, transparent); }
}
@media (prefers-reduced-motion: reduce) {
    .geo-map-marker-designcomuni--active .geo-map-marker-designcomuni__circle {
        animation: none;
        box-shadow: 0 0 0 3px #fff, 0 0 0 6px var(--marker-color);
    }
}

/* Dark mode adjustments */
@media (prefers-color-scheme: dark) {
    .geo-map-marker-designcomuni {
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5)) drop-shadow(0 4px 8px rgba(0,0,0,0.3));
    }
    .leaflet-marker-icon.geo-map-marker-wrapper--designcomuni:focus-visible .geo-map-marker-designcomuni__circle {
        box-shadow: 
            0 0 0 3px #fff,
            0 0 0 5px #4d9fff,
            0 0 12px rgba(0,0,0,0.4);
    }
}

/* Responsive by zoom (handled via JS map zoomend event) */
.geo-map-marker-designcomuni--zoom-low { width: 32px; height: 32px; }
.geo-map-marker-designcomuni--zoom-low .geo-map-marker-designcomuni__circle { width: 32px; height: 32px; }
.geo-map-marker-designcomuni--zoom-low .geo-map-marker-designcomuni__icon { width: 16px; height: 16px; }
.geo-map-marker-designcomuni--zoom-high { width: 48px; height: 48px; }
.geo-map-marker-designcomuni--zoom-high .geo-map-marker-designcomuni__circle { width: 48px; height: 48px; }
.geo-map-marker-designcomuni--zoom-high .geo-map-marker-designcomuni__icon { width: 24px; height: 24px; }
```

### 3. Integrazione in `events.js` (`updateMarker` / `syncMarkerToProperties`)
- Aggiungere logica per toggle class `--zoom-low` / `--zoom-high` su `map.on('zoomend')`
- Passare `typeLabel` o `color` basato su step/tipo ticket per colore dinamico
- Aggiungere `bindTooltip` con indirizzo per accessibilità

### 4. Test Manuale
- [ ] Aprire `http://localhost:8000/it/tickets/create?step=form.dati-di-segnalazione::data::wizard-step`
- [ ] Verificare marker visibile su street, satellite, hybrid
- [ ] Testare hover, focus (Tab), drag
- [ ] Verificare `prefers-reduced-motion` disabilita animazioni
- [ ] Verificare dark mode (OS o browser dev tools)
- [ ] Testare screen reader (NVDA/VoiceOver) → annuncia "Marker posizione segnalazione: [indirizzo]"

## Non-Scopi
- Cambiare logica backend salvataggio coordinate
- Modificare `CoordinatePicker` PHP (solo JS/CSS marker)
- Aggiungere clustering (non serve per singolo marker)

## Dependency
- Sprite SVG DesignComuni già presente in `public/themes/Sixteen/design-comuni/...`
- Story correlate: `03-tickets-create-segnalazione-flow` (usa questo marker), `09-universal-components-cmp-library` (pattern cmp-map-picker)