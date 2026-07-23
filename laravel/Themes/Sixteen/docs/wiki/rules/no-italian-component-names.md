---
title: "No Italian File Names in Code"
type: rule
confidence: high
created: 2026-05-29
updated: 2026-06-10
tags: [naming, components, css, js, i18n, dry, uniformita]
issues:
  - https://github.com/laraxot/base_fixcity_fila5/issues/264
  - https://github.com/laraxot/theme_sixteen_fila5/issues/58
discussions:
  - https://github.com/laraxot/base_fixcity_fila5/discussions/265
related:
  - ../../../../../docs/wiki/bmad/architecture-r12-english-only-view-paths.md
  - ../../../../../docs/wiki/memories/english-only-file-naming.md
  - ../concepts/ticket-list-map-integration.md
  - ../../../../../docs/wiki/rules/no-italian-folder-names-in-code.md
  - ../../../../../Modules/Geo/docs/wiki/rules/js-file-english-naming-rule.md
  - ../../../../../.cursor/rules/english-only-naming.mdc
---

# Regola: Solo Inglese nei Nomi File

## Principio

**Ogni filename** nel codebase — CSS, Blade, JS, PHP, JSON — deve essere in **inglese**, mai in italiano. L'italiano vive solo in `lang/`, route slug pubblici, e label UI.

## Vietato

| Termine italiano | Esempi violazione |
|-----------------|-------------------|
| `segnalazione` | `segnalazione-parity.css`, `segnalazione-wizard.css` |
| `segnalazioni` | `components/blocks/segnalazioni/` |
| `comuni` (comune) | `design-comuni-*.css` |
| `argomenti` | `argomenti-parity.css` |
| `servizi` | `servizi-parity-fix.css` |
| `amministrazione` | `amministrazione-parity-fix.css` |
| `disservizio` | `disservizio-lista.json` |
| `pratic*` | cartelle con `pratica` |
| `mappa` | `mappa-filtro.js` |

## Obbligatorio

| Contesto | Inglese | Esempio |
|----------|---------|---------|
| CSS theme | `ticket-parity.css`, `service-parity.css` | `resources/css/ticket-parity.css` |
| Blade componenti | `ticket/`, `news/`, `service/` | `components/blocks/ticket/filters.blade.php` |
| CTA block | `cta/ticket` non `cta/segnalazione` | `components/blocks/cta/ticket.blade.php` |
| JS modulo Geo | `popup-ticket.js` | `Modules/Geo/resources/js/map/popup-ticket.js` |
| Nomi funzione JS | `buildTicketPopupHtml` | non `buildSegnalazionePopupHtml` |
| JSON config | `ticket-types.json` | `resources/json/ticket-types.json` |
| Path Moduli | `Ticket`, `News`, `Service` | `Modules/Ticket/` |
| Models | `Ticket`, `Service` | `Ticket::class` |

## Perché

1. **Uniformità** — Un solo linguaggio nel codebase = zero switch cognitivo
2. **DRY** — La traduzione italiana (`segnalazione`) è già nei file `lang/it/`
3. **Consistenza** — `Ticket` Model, `ticket.js`, `ticket-parity.css`, `ticket.blade.php`
4. **Internazionalizzazione** — Il codice deve essere language-agnostic
5. **Ricerca** — `grep`, import Vite, glob — un vocabolario unico

## Eccezioni (permesse)

- `lang/{locale}/*.php` — traduzioni
- Route slug pubblici: `/it/segnalazione/crea`
- `data-page="ticket-list"` — slug pagina parity Design Comuni
- `_bmad-output/` — artefatti BMAD (possono usare italiano descrittivo)
- `docs/` — documentazione (può usare italiano, ma preferire inglese)
- Commenti che citano URL Design Comuni: `// Reference: ticket-list.html`

## Verifica

```bash
# Cerca file CSS con italiano (violazioni)
find laravel/Themes/Sixteen/resources/css -name '*segnalazi*' -o -name '*argomenti*' -o -name '*servizi*' -o -name '*comuni*' -o -name '*amministrazione*' | grep -v node_modules

# Cerca file JS con italiano
find laravel/Modules/Geo/resources/js -name '*segnalazione*' -o -name '*mappa*' -o -name '*filtro*'

# Cerca cartelle italiane
find . -type d -name "segnalazi*" -o -name "pratic*" -o -name "servizi*" | grep -v -e lang -e node_modules -e _bmad -e docs

# Deve restituire solo debt legacy documentato
```


## No italiano in `__()` e attributi accessibilità

**Vietato** usare parole italiane come chiave `__()` o testo letterale negli attributi `aria-label` / `title` nei Blade:

| ❌ Vietato | ✅ Corretto |
|-----------|------------|
| `__('Chiudi')` | `__('pub_theme::ui.close')` |
| `aria-label="Chiudi"` | `aria-label="{{ __('pub_theme::ui.close') }}"` |
| `__('Filtra')` | `__('fixcity::ticket.filter.button.label')` |

`__()` accetta **solo** chiavi i18n inglesi (`namespace::path.to.key`), mai copy UI italiana come chiave.
Il valore tradotto vive in `lang/{locale}/`, non nel template.

Canon esteso: [no-italian-in-html-attributes.md](../../../../../docs/wiki/rules/no-italian-in-html-attributes.md) · [no-italian-in-methods-and-blades.md](../../../../../docs/wiki/agents/rules/no-italian-in-methods-and-blades.md)

## Stato rename (2026-06-10)

Completati in `resources/css/` (import `app.css` aggiornati): `ticket-parity`, `topics-parity`, `civic-design-*`, `services-parity-fix`, `administration-parity-fix`, stub `ticket-wizard-deprecated`.

Blade componenti CMS:
- `components/blocks/tests/segnalazione-dettaglio.blade.php` → `ticket-detail.blade.php` (2026-06-10). Riferimenti `view` aggiornati in `config/local/fixcity/database/content/pages/tests.ticket-dettaglio.json` e `tests.ticket-disservizio.json`. Slug pubblici (`tests.segnalazione-dettaglio`) lasciati invariati: ammessi dalla regola.

Debt residuo: `js/design-comuni.js` → `civic-design.js` (se presente). Dettaglio: [css-filename-english-naming.md](../../architecture/css-filename-english-naming.md).
