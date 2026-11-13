---
title: "Regola: componenti riutilizzabili"
type: rule
status: active
theme: Sixteen
created: 2026-09-01
updated: 2026-10-22
tags: [sixteen, reusable, dry, kiss, bmad, filament-blueprint]
qmd: "reusable components blocks support actions folio theme sixteen"
related:
  - ../wiki/concepts/design-comuni-reusable-components.md
  - ../bmad/filament-blueprint.md
  - ../bmad/container0-index-shell-contract.md
  - ../stories/STORY-BLOG003-reusable-components.md
---

# Regola: Componenti Riutilizzabili

## Principio (sempre)

Ogni pezzo FO (blocco Blade, Infrastructure/Presentation, Action) deve essere **riutilizzabile**.
Logica specifica di pagina non vive nel Blade Folio né in `@php` grasso:
va in Action / layer semantici (`Infrastructure`, `Presentation`, `Adapters`) o in dati CMS JSON.
**Mai** `app/Support/` nel tema o nei moduli.

## Perché (business)

- Una shell Folio con alias/404 inline non si testa e non si riusa.
- URL `container0.*` ripetuti nei Blade diventano una seconda verità.
- BMAD + Filament blueprint: il tema **veste**; dominio e policy restano nei moduli.

## Regole

1. Usa `@props` per parametri esterni; default da Support tipizzati.
2. Non hard-codare URL o slug specifici nel Blade — usa `FrontofficeRoutes` / JSON.
3. Presentazione nel Blade; dati e decisioni in Action / Infrastructure / Presentation (`ThemeComposer` / QueueableAction).
4. Folio `[container0]/index` = solo trasporto: `ResolveContainer0IndexAction` per alias/404/area-personale.
5. Search modal: suggerimenti da `FrontofficeSearchSuggestions` (override via `@props links`).
6. Label UI solo via chiavi i18n (`pub_theme::` / modulo owner) — mai stringhe hardcoded.
7. Parità visiva: 90% HTML / 95% visual rispetto a `design-comuni-pagine-statiche`.
8. Header e footer consumano lo stesso `brand.logo` dal JSON e lo stesso partial
   `sections/partials/brand-mark` (SVG 82×82). Vietato `<img>` solo nel footer o
   sprite `it-pa` al posto del marchio.
   <!-- Harness: agenti FO Sixteen / BMAD. Cambia: vestito chrome brand. Già coperto
        da header-footer-sections-contract; raffina regola 8. Istruzione: logo footer = header. -->
9. Aggiorna `docs/stories/` o `docs/bmad/` quando nasce un pezzo riusabile nuovo.

## Layer FO riusabili (tema — ex Support → Infrastructure/Presentation/Actions)

| Classe | Namespace | Ruolo |
|--------|-----------|--------|
| `FrontofficeRoutes` | `Infrastructure\Url` | `route('container0.*')` tipizzato (+ tickets*, faq, blog, events, services, personalArea*) |
| `FrontofficeContainerAliases` | `Infrastructure\Url` | mappa IT storici → container canonici |
| `FrontofficeUrl` | `Infrastructure\Url` | path localizzati da CMS JSON |
| `FrontofficeSearchSuggestions` | `Presentation\Search` | default link search-modal |
| `PageDescription` | `Presentation\Metadata` | meta description multilingua |
| `ResolveContainer0IndexAction` | `Actions\Folio` | redirect 301 / area-personale / abort 404 |

## Riferimento BMAD

- Story: [STORY-BLOG003-reusable-components.md](../stories/STORY-BLOG003-reusable-components.md)
- Second brain: [design-comuni-reusable-components.md](../wiki/concepts/design-comuni-reusable-components.md)
- Blueprint: [filament-blueprint.md](../bmad/filament-blueprint.md)
- Shell: [container0-index-shell-contract.md](../bmad/container0-index-shell-contract.md)
