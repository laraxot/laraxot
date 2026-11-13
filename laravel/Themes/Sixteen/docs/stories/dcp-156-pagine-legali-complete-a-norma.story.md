---
type: story
tags: [legal, gdpr, accessibility, compliance, multilingua]
created: 2026-10-14
updated: 2026-10-14
qmd: "pagine legali disclaimer privacy cookie-policy accessibilita a norma"
issues: []
discussions: []
id: "2026-10-14-dcp-156"
epic: 156
title: "Pagine legali complete (disclaimer, privacy, cookie-policy, note-legali, accessibilita)"
slug: pagine-legali-complete-a-norma
status: ready-for-dev
scope: module:Gdpr, theme:Sixteen
github:
  repository: https://github.com/laraxot/module_gdpr_fila5
  issues: https://github.com/laraxot/module_gdpr_fila5/issues/56
  discussions: https://github.com/laraxot/module_gdpr_fila5/discussions/57
  issue_status: "aperta"
related:
  - ../docs/stories/dcp-153-cookiebar-integrata-nel-modulo-gdpr.story.md
  - ../docs/stories/2026-10-14-dcp-154-cookie-consent-test-dcp-154.story.md
  - ../docs/stories/2026-10-14-dcp-155-cookie-category-enum-dcp-155.story.md
  - ../docs/stories/12.1.gdpr-owns-legal-pages.story.md
---

# 2026-10-14 DCP-156 Pagine legali complete (disclaimer, privacy, cookie-policy, note-legali, accessibilita)

Studiato TermsFeed, SOS-WP e Servizi Industria: un sito a norma di legge (art. 2250 c.c., art. 13 D.lgs. 196/03, provvedimento Garante cookie 8 maggio 2014, legge 62/2001) deve avere **cinque documenti separati e linkabili dal footer**, non un unico testo lungo:

1. **Disclaimer / Note legali** — limitazione di responsabilità, copyright, marchi, condizioni d'uso, collegamenti a terzi.
2. **Informativa privacy** — art. 13 GDPR: titolare, luogo trattamento, tipi di dati, diritti dell'interessato.
3. **Cookie policy** — elenco cookie (tecnici, analitici, di terze parti), durata, come revocare.
4. **Dichiarazione di accessibilità** — livello di conformità WCAG, procedure per segnalare problemi.
5. **Termini e condizioni** (opzionale, ma consigliato) — legge applicabile, foro competente.

Il progetto già ha:
- `Themes/Sixteen/resources/views/disclaimer.blade.php` (legacy, non multilingua, non collegata al footer).
- `config/.../pages/privacy.index.json`, `cookie-policy.index.json`, `note-legali.index.json`, `accessibilita.index.json` (contenuti tenant, multilingua, layout `design-comuni`).
- Footer con link a `/it/informativa-privacy`, `/it/note-legali`, `/it/dichiarazione-accessibilita` (ma **mancante** il link a `/it/cookie-policy`).

## Obiettivo

- Rendere il disclaimer blade.php **multilingua** (lang file) e collegarlo al footer.
- Aggiungere il link **cookie-policy** al footer (in tutti i layout: main, guest, design-comuni, exact-1to1).
- Garantire che tutte e cinque le pagine siano raggiungibili in **it, en, de, es**.
- Rispettare la regola BMAD: **non creare moduli/temi solo per documentazione**; i documenti legali vivono in `Modules/Fixcity` (owner dei contenuti tenant) e `Themes/Sixteen` (owner del layout).

## Acceptance Criteria

1. `Themes/Sixteen/resources/views/disclaimer.blade.php` usa `__('disclaimer.*')` (lang file in 4 lingue) invece di testo hardcodato.
2. Footer (main, guest, design-comuni, exact-1to1) contiene link a: privacy, note-legali, cookie-policy, accessibilita.
3. Tutte le pagine legali sono raggiungibili in it/en/de/es.
4. Nessun test rotto; `BootstrapItaliaComponentsTest` (cookiebar) passa.
5. Nessun file creativo in `app/Http/Controllers` (regola no-controllers).