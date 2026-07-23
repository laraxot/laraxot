---
type: entity
created: 2026-05-04
updated: 2026-05-15
tags: [design-comuni, bootstrap, tailwind, daisyui, mapping]
---

# CSS Class Mapping: Design Comuni (Bootstrap) to Sixteen (Tailwind)

This table maps original Bootstrap Italia / Design Comuni classes to our custom Tailwind implementation in the Sixteen theme.

| Design Comuni (Bootstrap) | Sixteen (Tailwind / Custom CSS) | Purpose |
|---------------------------|----------------------------------|---------|
| `.it-header-slim-wrapper` | `.it-header-slim-wrapper` | Top bar background & height |
| `.btn-full` (dark green)  | `.btn-full` (via `header-footer-colors.css`) | Login CTA styling |
| `.navbar-nav .nav-link`   | `.nav-link` (with `font-bold`)   | Navigation item weight |
| `.it-brand-wrapper`       | `.it-brand-wrapper`              | Logo container spacing |
| `.it-header-center-wrapper`| `.it-header-center-wrapper`      | Main header padding |
| `.nav-link.active`        | `.nav-link.active` (with `border-b-3`) | Current page indicator |

## Implementation Strategy
- We keep the semantic class names from Bootstrap Italia to ensure easy migration and automated analysis.
- The actual styling is provided by **Tailwind `@apply` rules** inside `resources/css/components/*.css` — **alias CSS centralizzati** sono il pattern preferito rispetto a lunghe catene di utility nel markup (vedi [bootstrap-italia-tailwind-philosophy](../concepts/bootstrap-italia-tailwind-philosophy.md)).
- **Constraint**: No Bootstrap Italia JS is used; interactivity is handled by Alpine.js.
- **Filament v5 / Lit**: il DOM non replica sempre `cmp-*` atom-for-atom; si usano layer di parity (es. `.fi-sc-wizard`, `.wizard-dc-form-shell`, `filament-wizard-parity.css`) per ottenere lo stesso linguaggio visivo senza cambiare i **token** e i **pattern** Design Comuni (colori, spessori, focus, stepper).
- **Scopo**: *nessun Bootstrap runtime* nel frontoffice; **sì** al contratto markup del modello (classi `.it-*`, `.cmp-*`, `.form-*` dove il riferimento HTML le usa) + HTML semantico allineato alle pagine statiche.

## DaisyUI (plugin Tailwind)

[DaisyUI](https://daisyui.com/docs/) è incluso nel tema **Sixteen** come plugin di Tailwind (`tailwind.config.js`, `package.json`: `daisyui`). Fornisce **classi semantiche** su componenti (`btn`, `card`, `modal`, …) senza JS proprietario.

**Come convive con Design Comuni**

- **Priorità visiva** su pagine istituzionali e parity PA: token e layout da riferimento [design-comuni-pagine-statiche](https://github.com/italia/design-comuni-pagine-statiche); classi `.it-*` / `.cmp-*` restano il contratto HTML dove il kit le usa.
- **DaisyUI** si usa per accelerare blocchi interni, admin-adjacent o ripetitivi **quando non entra in conflitto** con quel contratto (es. non sostituire l’header comuni con un `navbar` Daisy “generico” senza mappatura).
- **Filament v5** (`fi-*`) resta separato: eventu uso di utility Tailwind/Daisy su wrapper Blade deve rispettare [site-wide component rule](../concepts/design-comuni-site-wide-component-css-rule.md).

Linee guida dettagliate (progetto): [DaisyUI — modulo Cms](../../../../../Modules/Cms/docs/daisyui-componenti.md). **Pro/contro e percentuali:** [daisyui-pro-contro-metriche](../../../../../Modules/Cms/docs/daisyui-pro-contro-metriche.md). MCP blueprint: [UI mcp-ui-ux](../../../../../Modules/UI/docs/mcp-ui-ux.md).

## Architettura CSS nel repo ufficiale (riferimento)

Fonte: [italia/design-comuni-pagine-statiche — `src/stylesheets`](https://github.com/italia/design-comuni-pagine-statiche/tree/main/src/stylesheets).

L’entrypoint pubblicato come `bootstrap-italia-comuni.css` non è “solo override comuni”: [`bootstrap-italia-comuni.scss`](https://github.com/italia/design-comuni-pagine-statiche/blob/main/src/stylesheets/bootstrap-italia-comuni.scss) concatena, in ordine:

1. **Token comuni** — `_variables.scss` (colori PA, header, card, footer, `--bs-link-hover-color` su `:root`).
2. **Bootstrap 5 + Bootstrap Italia** — grid, form, componenti, utilities, poi tutti i `custom/*` del pacchetto `bootstrap-italia` (header, footer, steppers, form-*, ecc.).
3. **Layer “Comuni” nel repo** — `base/` (`_color`, `_reset`, `_global`, …), `custom/comuni-custom`, `general/` (splide, template pubblici, print).
4. **Componenti sito (`cmp-*`)** — import da **`src/components/cmp-*/`** (non sotto `stylesheets/`): es. [`cmp-input/_input.scss`](https://github.com/italia/design-comuni-pagine-statiche/blob/main/src/components/cmp-input/_input.scss) con blocchi `.cmp-input`, `.input-wrapper`; stesso pattern per `cmp-select`, `cmp-nav-steps`, `cmp-heading`, …

Per studiare un flusso (es. segnalazione dati): confrontare HTML di riferimento in `src/pages/…` / template Handlebars dei componenti con gli SCSS omonimi in `src/components/`. Le regole “globali” stanno in `stylesheets/`; lo **stile per nome componente** è co-localizzato col partial sotto `src/components/`.

---
*Reference: Story 7-105 & 7-107.*
