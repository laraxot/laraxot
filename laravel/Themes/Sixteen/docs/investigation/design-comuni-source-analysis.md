---
title: "Design Comuni Source Analysis — front-office pages + UI kit"
type: investigation
status: completed
links:
  github_issue: "https://github.com/laraxot/base_fixcity_fila5/issues/501"
  discussion: "https://github.com/laraxot/base_fixcity_fila5/discussions/501"
created: 2026-06-10
updated: 2026-06-10
tags: [design-comuni, bootstrap-italia, front-office, sixteenth, bmad, wcag]
confidence: high
related:
  - docs/wiki/rules/no-controllers-rule.md
  - docs/wiki/concepts/agent-bootstrap-compact.md
qmd: "design comuni   source analysis (v2.4.0) sixteen"
---

# Design Comuni — Source Analysis (v2.4.0)

**Scope**: map front-office pages requested from Design Comuni templates; document UI/UX patterns, tokens, components, group/tab structure; note WCAG 2.1 AA constraints; state Bootstrap Italia reference only (NO use of framework in code).

## Sources fetched
- `https://italia.github.io/design-comuni-pagine-statiche/` (main index, v2.4.0)
- `https://italia.github.io/design-comuni-pagine-statiche/servizi/index.html` (service flows)
- `https://designers.italia.it/kit/comuni/` → redirect `/modelli/comuni/`
- `https://github.com/italia/design-ui-kit` (UI Kit Italia, design-system resource)
- `https://italia.github.io/bootstrap-italia/` (visual reference, v2.18.3 — NOT to import)
- WCAG 2.1 AA reference: `https://mauve.isti.cnr.it/supported_wcag21.jsp`

## Design Comuni v2.4.0 — Template Pages (front-office required)

### Group: Generali (Sito)
- Homepage (`homepage.html`)
- Domande frequenti (`domande-frequenti.html`)
- Risultati ricerca (`risultati-ricerca.html`)
- Argomenti — lista (`argomenti.html`)
- Argomento — dettaglio (`argomento.html`)
- Lista contenuti/risorse (`lista-risorse.html`)
- Lista categorie (`lista-categorie.html`)
- Lista risorse e categorie (`lista-risorse-categorie.html`)
- Mappa del sito (`mappa-sito.html`)

### Group: Amministrazione
- Amministrazione (`amministrazione.html`)
- Documenti e dati (`documenti-dati.html`)

### Group: Novità
- Novità (`novita.html`)
- Notizia / Comunicato / Avviso — dettaglio (`novita-dettaglio.html`)

### Group: Servizi
- Servizi (`servizi.html`)
- Categoria servizio (`categoria-servizio.html`)
- Scheda servizio (`dettaglio-servizio.html`)

### Group: Vivere il Comune (Eventi)
- Eventi (`eventi.html`)
- Evento (`evento-dettaglio.html`)

### Group: Prenotazione Appuntamento (6-step wizard)
1. Selezione ufficio (`appuntamento-01-ufficio.html`)
2. Selezione ufficio — sede (`appuntamento-01-ufficio-luogo.html`)
3. Data / orario (`appuntamento-02-data-orario.html`)
4. Motivo + dettagli (`appuntamento-03-dettagli.html`)
5. Info personali — non autenticato (`appuntamento-04-richiedente.html`)
6. Info personali — autenticato (`appuntamento-04-richiedente-autenticato.html`)
7. Riepilogo (`appuntamento-05-riepilogo.html`)
8. Conferma (`appuntamento-06-conferma.html`)

### Group: Richiesta Assistenza (2-step)
- Inserimento dati (`assistenza-01-dati.html`)
- Conferma (`assistenza-02-conferma.html`)

### Group: Segnalazione Disservizio (multi-step)
- Scheda servizio (`segnalazione-dettaglio.html`)
- Privacy (`segnalazione-01-privacy.html`)
- Inserimento dati (`segnalazione-02-dati.html`)
- Riepilogo (`segnalazione-03-riepilogo.html`)
- Conferma (`segnalazione-04-conferma.html`)
- Area personale (`segnalazione-area-personale.html`)
- Elenco (`segnalazioni-elenco.html`)

### Group: Flussi di servizio (tab 2)
- Accesso servizio (`accesso-servizio.html`)
- Consenso privacy (`consenso-privacy.html`)
- Iscrizione a graduatoria (scheda, dati, specifici, riepilogo, conferma, area personale, messaggio, perfezionamento)
- Permessi/autorizzazioni (scheda, dati, specifici, riepilogo, conferma, area personale, pagamento)
- Vantaggi economici (scheda, dati, riepilogo, conferma, area personale)
- Pagamenti — multa (scheda, dati personali, dati specifici fase 1-3, riepilogo, conferma, area personale)
- Pagamenti — IMU (scheda, dati personali, riepilogo, preferenze, anteprima F24, conferma, area personale)
- Servizi a pagamento (scheda, dati, specifici, riepilogo, conferma, area personale, pagamento)

## Pattern UI/UX (observed from templates)
- **Layout**: 2-column with sticky left nav (`it-navscroll-wrapper`) on service pages; single column on site pages.
- **Navigation**: tab-based (Sito / Flussi di servizio) at top level; left sticky index with anchors (`#generali`, `#amministrazione`, etc.).
- **Components used**: `it-page-section`, `it-list-wrapper`, `list-item` cards, `nav-tabs`, `navscroll`, sticky `col-md-4`, `text-secondary` headings.
- **Content structure**: title + description (`<em>`) + metadata tag (`<span class="metadata">filename.html</span>`); no Bootstrap cards/shadow as primary separator — list items with hairline separators.
- **Step wizard**: numbered steps shown implicitly by progression, no progress bar in template — user must implement.

## Token / Color / Typography (Bootstrap Italia v2.18.3 reference — NOT to import)
- Primary accent from theme (visible): `#0066CC` (theme-color meta, `btn-primary`).
- Typography: Bootstrap 5 / Italia design system uses `Lora Serif` (display) + `Titillium Web` (body) typically; templates rely on system/Bootstrap defaults.
- No custom gradient/glow patterns observed — clean white/card layout.
- Elevation: minimal; hairline borders separate list items rather than shadow cards.
- WCAG 2.1 AA: contrast must be validated via APCA (Lc ≥75 body, ≥45 large-bold); dark-theme default is an AI anti-pattern (per ux-design rules) — use only if brand requests.

## Components observed (Design Comuni → map to Sixteen components)
- `it-page-section` → `Sixteen/Components/Sections/PageSection`
- `it-list-wrapper` + `list-item` → `Sixteen/Components/Cards/ListItem` (with `it-right-zone`)
- `nav-tabs` (Sito / Flussi) → `Sixteen/Components/Navigation/Tabs`
- `navscroll` sticky sidebar → `Sixteen/Components/Navigation/NavScroll`
- `it-back-button` → `Sixteen/Components/Buttons/Back`
- `btn btn-primary` (accesso-servizio) → `Sixteen/Components/Buttons/Primary`
- `sticky-top` sidebar + `col-md-4/col-md-8` split → `Sixteen/Layout/TwoColumnSticky`

## Structure Groups / Tabs
- **Sito** tab: Generali, Amministrazione, Novità, Servizi, Vivere il Comune, Prenotazione Appuntamento, Richiesta Assistenza, Segnalazione Disservizio.
- **Flussi di servizio** tab: Generali, Graduatoria, Permessi, Vantaggi economici, Pagamenti dovuti (multa / IMU), Servizi a pagamento.

## Key constraints (from AGENTS.md / project rules)
- NO Bootstrap Italia in production code (only reference); use Tailwind / design tokens.
- No controllers — Folio pages + Actions (`app/Actions/*Action.php`).
- No services layer — business logic in Actions.
- Module contracts: `*Contract` suffix, `app/Contracts/` or `Models/Contracts/`; no `app/Interfaces/`.
- BMAD: every story needs GitHub issue + Discussion links; frontmatter mandatory.
- Quality gate: `bash bashscripts/quality-gates/verify-llm-wiki.sh` after edits; lint with `npx @google/design.md lint DESIGN.md`.

## Next steps (open for implementation)
- Build Folio pages for each group/tab (start with Generali + Amministrazione + Servizi).
- Implement wizard steps (Appuntamento 01-06) as progressive form actions.
- Implement service-flow templates with sticky nav (graduatoria / permessi / pagamenti).
- Verify contrast (APCA + WCAG sidecar) and `prefers-reduced-motion` on wizard transitions.
- Persist token choices to repo-root `DESIGN.md` (preset floor, then bend via Step 1.5 direction).
