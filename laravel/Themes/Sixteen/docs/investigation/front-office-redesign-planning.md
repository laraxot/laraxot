---
title: "Pianificazione redesign front office con DesignComuni"
type: investigation
tags: [design-comuni, front-office, planning, wcss-21-AA]
links:
  story: "docs/stories/front-office-complete-redesign.story.md"
  design_ref: "https://italia.github.io/design-comuni-pagine-statiche/index.html"
  homepage_ref: "https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html"
  segnalazione_ref: "https://italia.github.io/design-comuni-pagine-statiche/index.html#segnalazione"
  github_ref: "https://github.com/italia/design-comuni-pagine-statiche"
  tech_spec: "docs/bmad/front-office-redesign-tech-spec.md"
  prd: "docs/bmad/front-office-redesign-prd.md"
  decision_log: "docs/decision-log.md"
status: draft
updated: 2026-10-11
qmd: "pianificazione redesign front office con designcomuni sixteen"
---

# Pianificazione redesign front office con DesignComuni

## Scopo e ambito
Riallineare tutte le pagine front office del tema Sixteen (`home`, `tickets/*`, `categories/*`, `errori`, `accessibilità`) al design system `design-comuni-pagine-statiche` v2.4.0, con le seguenti regole vincolanti:

1. **No CDN Bootstrap Italia** — tutte le classi Bootstrap devono essere mappate in Tailwind via `@apply` o componenti personalizzati.
2. **WCAG 2.1 AA** — contrasto minimo 4.5:1 (testo), 3:1 (UI components), focus-visible sugli interattivi, skip link obbligatori.
3. **Header e Footer DesignComuni** — `it-header-wrapper`, `it-footer` con skip link, EU logo, it-brand-text.
4. **Marker mappa** — `L.divIcon` con SVG a contrasto elevato (es. `#007a52` riempito, `#ffffff` bordo), `outline` focus-visible per accessibilità.
5. **Flusso segnalazione** — 4 step (privacy → dati → riepilogo → conferma) con wizard Livewire + validazione proprietaria.
6. **Nessun componente Blazed** — evitare `x-pub_theme::layouts.app` per le nuove pagine; usare layout nativo o composizione di blocchi.

## Pagine target e state attuale

| Pagina | State attuale | Gap principale |
|---|---|---|
| `home.blade.php` / `index.blade.php` | Inizio | Header/footer non DesignComuni; marker mappa default Leaflet; hero non conforme |
| `tickets/index.blade.php` | Parziale | Marker mappa Leaflet standard; classe `.marker` non visibile; assenza `.ticket-marker-visible` |
| `tickets/create/*.blade.php` | In corso | `owner_id` null (errore 500); wizard non allineato a DesignComuni |
| `tickets/show.blade.php` | Non avviato | Nessuna struttura DesignComuni |
| `tickets/track.blade.php` | Non avviato | Form ricerca codice non esistente |
| `categories/*.blade.php` | Non avviato | Layout elenco categorie generico |
| `errors/*.blade.php` | Non avviato | 404/500 generici, nessun pattern DesignComuni |
| `accessibility.blade.php` | Non avviato | Nessuna dichiarazione di accessibilità |

## Risorse tecniche
- **HTML**: template `homepage.html` + `segnalazione-dettaglio.html` da `design-comuni-pagine-statiche`.
- **CSS**: `design-comuni.css` locale (mappatura Bootstrap → Tailwind), `app.css` Tailwind v4, asset `svg/sprites.svg` Bootstrap Italia.
- **JS**: Leaflet 1.9.4 + MarkerCluster (fallback no CDN), AlpineJS per stato, Lit per componenti web.
- **Symlink**: `public_html/themes/Sixteen → laravel/Themes/Sixteen/public`.
- **Componenti**: `map-lit` Web Component, `design-comuni.blade.php` layout.

## Open questions
1. Wizard segnalazione: quale componente validare per la privacy (obbligatorio `GetPublishedPrivacyPolicyAction`)?
2. Modalità fallback per vecchi browser che non supportano `L.divIcon`?
3. Come gestire le immagini di sfondo `bg-primary` (colore `#007a52`) coerente in tutto il sito?

## Prossimi passi BMAD
1. ✅ Story completa (`front-office-complete-redesign.story.md`).
2. ✅ Investigation completata (`front-office-redesign-planning.md`).
3. ✅ Tech spec in `docs/bmad/front-office-redesign-tech-spec.md`.
4. ✅ PRD in `docs/bmad/front-office-redesign-prd.md`.
5. ✅ Decision log in `docs/decision-log.md`.
6. Implementazione pagina per pagina (non in questa sessione per evitare rischi).