---
id: "sixteen-fo-09-universal-components-cmp"
type: "epic-story"
title: "Universal components (cmp-*) library for Design Comuni"
description: "Creare libreria di componenti universali cmp-* basati su design-comuni-pagine-statiche per sostituire componenti custom e Bootstrap Italia: cmp-hero, cmp-card, cmp-badge, cmp-button, cmp-form-field, cmp-step-indicator, cmp-timeline, cmp-breadcrumb, cmp-pagination, cmp-empty-state, cmp-illustration, cmp-category-grid, cmp-map-static, cmp-social-share, cmp-comment-thread, cmp-file-upload, cmp-autocomplete-address, cmp-map-picker, cmp-search-modal, cmp-dropdown, cmp-language-switcher, cmp-brand, cmp-footer-list, cmp-footer-bottom, cmp-nav-sidebar, cmp-filter-sidebar, cmp-alert, cmp-toast, cmp-modal, cmp-accordion, cmp-faq, cmp-sitemap, cmp-auth-form, cmp-stat-card, cmp-feature-card, cmp-cta-section, cmp-page-section, cmp-table, cmp-progress, cmp-avatar, cmp-badge-status, cmp-chip, cmp-tag, cmp-tooltip, cmp-popover, cmp-tabs, cmp-table-of-contents, cmp-back-to-top, cmp-cookie-banner, cmp-announcement-bar."
frontmatter:
  github_issue: "#63"
  github_discussion: "#63"
links:
  github_issue: "#63"
  github_discussion: "#63"
  design_ref: "https://italia.github.io/design-comuni-pagine-statiche/"
  investigation_ref: "docs/investigation/redesign-front-office-pages-investigation.md"
---

# Universal Components (cmp-*) Library DesignComuni — Epic Story

## Goal
Creare libreria completa di componenti universali `cmp-*` basati su `design-comuni-pagine-statiche` per sostituire tutti i componenti custom e rimuovere dipendenza Bootstrap Italia CDN. Ogni componente deve usare Tailwind CSS tramite `@apply` (non classi Bootstrap), essere accessibile (WCAG 2.1 AA), supportare `prefers-reduced-motion`, multi-lingua e dark mode.

## Background
- Referenza: `https://github.com/italia/design-comuni-pagine-statiche` → componenti universali
- Componenti attuali nel tema: `components/blocks/**/*.blade.php` (100+ file, misto Bootstrap/custom)
- Obiettivo: consolidare in ~40 componenti universali riutilizzabili
- Token design: estendere `tailwind.config.js` con colori DesignComuni (primary #0057b8, secondary #002b5c, success #28a745, warning #ffc107, danger #dc3545, info #17a2b8)

## Acceptance Criteria

### Componenti Layout & Structure
- [ ] **cmp-page-section** — wrapper sezione standard: `it-page-section` + `container` + slot per header/content/footer
- [ ] **cmp-container** — container responsive con breakpoint custom
- [ ] **cmp-grid** — griglia 12 colonne con gap responsive (Tailwind `grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6`)
- [ ] **cmp-stack** — vertical stack con spacing uniforme (`space-y-6`)

### Componenti Hero & Marketing
- [ ] **cmp-hero** — hero section: titolo, sottotitolo, 2 CTA (primaria + secondaria), background image/gradient opzionale, `aria-labelledby`
- [ ] **cmp-hero-split** — hero diviso: content + media (immagine/video/mappa), responsive stack su mobile
- [ ] **cmp-feature-card** — card feature: icona, titolo, descrizione, CTA opzionale, variant (default, bordered, elevated)
- [ ] **cmp-stat-card** — card statistica: valore grande, label, icona, trend (up/down), background color variant
- [ ] **cmp-cta-section** — sezione CTA: titolo, descrizione, bottone primario, background color variant

### Componenti Card & Content
- [ ] **cmp-card** — card base: `card border-0 shadow-sm` + slot header/body/footer, variant (default, bg, bordered, elevated)
- [ ] **cmp-card-grid** — griglia responsive di `cmp-card` con `auto-fit` / `minmax(300px, 1fr)`
- [ ] **cmp-category-grid** — griglia categorie: icona SVG sprite, titolo, descrizione, badge contatore, CTA link
- [ ] **cmp-accordion** — accordion accessibile: `details/summary` + Alpine.js per animazione, `aria-expanded`
- [ ] **cmp-faq** — FAQ: lista `cmp-accordion` con schema.org FAQPage markup
- [ ] **cmp-tabs** — tabs accessibili: `role="tablist"`, `aria-selected`, `aria-controls`, keyboard navigation
- [ ] **cmp-table** — table responsive: `table-responsive` + `table` + `thead`/`tbody`, sortable, striped, hover

### Componenti Form & Input
- [ ] **cmp-form-field** — wrapper field: label, input/select/textarea, error message (`aria-describedby`), hint, required asterisk
- [ ] **cmp-button** — button: variant (primary, secondary, outline, ghost, danger), size (sm, md, lg), loading state, disabled, `aria-busy`
- [ ] **cmp-button-group** — gruppo bottoni: `btn-group` con `role="group"`, `aria-label`
- [ ] **cmp-file-upload** — drag-drop upload: zona drop, preview, progress, remove, max files/size/types, `aria-live` per progress
- [ ] **cmp-autocomplete-address** — autocomplete indirizzo: Mapbox Geocoding, debounce, `aria-autocomplete="list"`, `aria-controls`
- [ ] **cmp-map-picker** — mappa interattiva per selezione punto: `<map-lit>` + marker draggable, coordinate output, `aria-label`
- [ ] **cmp-search-modal** — modal ricerca: trigger button, modal `role="dialog"`, `aria-modal`, focus trap, ESC per chiudere
- [ ] **cmp-dropdown** — dropdown menu: trigger button, `role="menu"`, `aria-expanded`, keyboard nav (freccia su/giù, ESC)
- [ ] **cmp-language-switcher** — selettore lingua: dropdown con bandiere, `localizeURL`, `hreflang` links
- [ ] **cmp-auth-form** — form auth: email/password, remember me, forgot password, register link, validation, `autocomplete`

### Componenti Navigation & Wayfinding
- [ ] **cmp-breadcrumb** — breadcrumb: `nav aria-label="Breadcrumb"`, `ol` + `li` + `a`, separator `>`, `aria-current="page"`
- [ ] **cmp-pagination** — paginazione: `nav aria-label="Pagination"`, `ul.pagination`, prev/next, numeri, `aria-current`, `rel="prev/next"`
- [ ] **cmp-nav-sidebar** — sidebar navigazione: `nav` + `ul` + `li` + `a`, active state, collapsible su mobile, `aria-label`
- [ ] **cmp-filter-sidebar** — sidebar filtri: `cmp-form-field` multipli, `cmp-button` apply/reset, `aria-label="Filtri"`
- [ ] **cmp-step-indicator** — step indicator: `ol` + `li` con `aria-current="step"`, connector line, label + number, responsive
- [ ] **cmp-back-to-top** — back to top: fixed bottom-right, `aria-label="Torna su"`, show on scroll > 200px, smooth scroll

### Componenti Feedback & Status
- [ ] **cmp-badge** — badge: variant (primary, secondary, success, warning, danger, info), size (sm, md), pill/rounded, dot indicator
- [ ] **cmp-badge-status** — badge stato specifico: aperto (verde), in lavorazione (giallo), chiuso (blu), respinto (rosso), bozza (grigio)
- [ ] **cmp-chip** — chip: label + remove button (opzionale), `aria-label` su remove
- [ ] **cmp-tag** — tag: link style, variant, `href`
- [ ] **cmp-alert** — alert: variant (info, success, warning, danger), dismissible, `role="alert"`, `aria-live="polite"`
- [ ] **cmp-toast** — toast: portal, stack, auto-dismiss, `aria-live="polite"`, action button opzionale
- [ ] **cmp-modal** — modal: `role="dialog"`, `aria-modal`, focus trap, ESC close, backdrop click close, `aria-labelledby`
- [ ] **cmp-tooltip** — tooltip: `role="tooltip"`, `aria-describedby`, delay show/hide, position (top/bottom/left/right)
- [ ] **cmp-popover** — popover: trigger click, `role="dialog"`, focus management, close on outside click

### Componenti Media & Data
- [ ] **cmp-map-static** — mappa statica: `<map-lit>` read-only, marker singolo, no controls, `aria-label`
- [ ] **cmp-social-share** — condivisione social: dropdown/button group, FB/Twitter/LinkedIn/WhatsApp/Email, `aria-label`, `target="_blank" rel="noopener"`
- [ ] **cmp-comment-thread** — thread commenti: lista commenti (avatar, nome, data, contenuto), form reply, `cmp-accordion` per replies
- [ ] **cmp-timeline** — timeline verticale: connector line, dot per step, label + data + contenuto, variant (default, alternate)
- [ ] **cmp-avatar** — avatar: immagine/placeholder, fallback iniziali, size (xs, sm, md, lg, xl), shape (circle, square), status badge
- [ ] **cmp-progress** — progress bar: `role="progressbar"`, `aria-valuenow/min/max`, variant (determinate, indeterminate), label
- [ ] **cmp-table-of-contents** — TOC: heading extraction, `nav` + `ol` + `a`, sticky su scroll, `aria-label="Indice"`

### Componenti Illustrative & Empty
- [ ] **cmp-empty-state** — empty state: illustrazione SVG, titolo, descrizione, CTA primaria, `role="status"`
- [ ] **cmp-illustration** — illustrazione decorativa: SVG inline, `aria-hidden="true"`, size responsive, color variant

### Componenti Institutional
- [ ] **cmp-brand** — brand comune: logo SVG + testo (titolo + tagline), link homepage, `aria-label`
- [ ] **cmp-footer-list** — lista footer: `ul.it-footer-list` + `li` + `a.list-item`
- [ ] **cmp-footer-bottom** — footer bottom: copyright + link legali + badge DesignComuni
- [ ] **cmp-cookie-banner** — cookie banner: `role="dialog"`, `aria-modal`, accetta/rifiuta/preferenze, `localStorage` consent
- [ ] **cmp-announcement-bar** — banner annuncio: dismissible, link, variant (info, warning, critical), `role="status"`

## Requisiti Tecnici Comuni
- [ ] **Tailwind `@apply`**: niente classi Bootstrap (`btn`, `card`, `badge`, etc.) — solo `cmp-*` custom
- [ ] **Sprite SVG**: icone da `/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-{icon}`
- [ ] **Accessibilità**: WCAG 2.1 AA, `prefers-reduced-motion`, focus-visible, `aria-*` appropriati
- [ ] **Dark mode**: supporto `dark:` variant per tutti i componenti
- [ ] **Multi-lingua**: testi via props o `__('...')`, niente hardcode
- [ ] **Type hints**: `@props` con tipi PHP (array, string, bool, int, ?)
- [ ] **Documentazione**: ogni componente ha `docs/components/cmp-{name}.md` con props, slot, esempi
- [ ] **Test**: component test con Pest + Laravel View Testing

## Struttura File
```
resources/views/components/
├── cmp-hero.blade.php
├── cmp-hero-split.blade.php
├── cmp-feature-card.blade.php
├── cmp-stat-card.blade.php
├── cmp-cta-section.blade.php
├── cmp-page-section.blade.php
├── cmp-container.blade.php
├── cmp-grid.blade.php
├── cmp-stack.blade.php
├── cmp-card.blade.php
├── cmp-card-grid.blade.php
├── cmp-category-grid.blade.php
├── cmp-accordion.blade.php
├── cmp-faq.blade.php
├── cmp-tabs.blade.php
├── cmp-table.blade.php
├── cmp-form-field.blade.php
├── cmp-button.blade.php
├── cmp-button-group.blade.php
├── cmp-file-upload.blade.php
├── cmp-autocomplete-address.blade.php
├── cmp-map-picker.blade.php
├── cmp-search-modal.blade.php
├── cmp-dropdown.blade.php
├── cmp-language-switcher.blade.php
├── cmp-auth-form.blade.php
├── cmp-breadcrumb.blade.php
├── cmp-pagination.blade.php
├── cmp-nav-sidebar.blade.php
├── cmp-filter-sidebar.blade.php
├── cmp-step-indicator.blade.php
├── cmp-back-to-top.blade.php
├── cmp-badge.blade.php
├── cmp-badge-status.blade.php
├── cmp-chip.blade.php
├── cmp-tag.blade.php
├── cmp-alert.blade.php
├── cmp-toast.blade.php
├── cmp-modal.blade.php
├── cmp-tooltip.blade.php
├── cmp-popover.blade.php
├── cmp-map-static.blade.php
├── cmp-social-share.blade.php
├── cmp-comment-thread.blade.php
├── cmp-timeline.blade.php
├── cmp-avatar.blade.php
├── cmp-progress.blade.php
├── cmp-table-of-contents.blade.php
├── cmp-empty-state.blade.php
├── cmp-illustration.blade.php
├── cmp-brand.blade.php
├── cmp-footer-list.blade.php
├── cmp-footer-bottom.blade.php
├── cmp-cookie-banner.blade.php
├── cmp-announcement-bar.blade.php
```

## Non-Scopi
- Componenti admin/Filament — separati
- Componenti API — non servono
- JavaScript framework — solo Alpine.js per interazioni leggere

## Dependency
- Story correlate: tutte le story FO (01-08) usano questi componenti
- Tailwind config: `tailwind.config.js` esteso con token DesignComuni
- Sprite SVG: asset già presente in `public/themes/Sixteen/design-comuni/...`