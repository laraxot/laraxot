---
title: "mcp validation fo pages"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "mcp validation fo pages"
issues: []
discussions: []
status: draft
---

# Validazione MCP — pagine FO Sixteen

**Boundary:** il tema Sixteen **non** implementa motori MAUVE/PSI/GSC; espone le URL FO che entrano nel gate agente ([mcp-validation-quality-gate.md](../../../../../../docs/wiki/mcp-validation-quality-gate.md)).

**Story:** [STORY-137](../../../../../../docs/stories/STORY-137-mcp-validation-mauve-pagespeed-gsc.md)

---
## URL smoke (ordine consigliato)

| Priorità | Path | Perché |
|----------|------|--------|
| 1 | `/it/auth/login` | Form Filament, label, errori, contrasto ([auth-login-ux-fixes](./auth-login-ux-fixes.md)) |
| 2 | `/it/tickets/{id}` | Widget `Ticket\ViewWidget` + Infolist, mappa, media |
| 3 | `/it` | Homepage, map-lit, hero |
| 4 | Elenco segnalazioni | Tabs Filament 5.x, filtri |

> Aggiornamento 2026-10-14: la riga 2 (`/it/tickets/{id}` = Widget `Ticket\ViewWidget`) e' superata. un `/{lang}/tickets/{id}` con id numerico e' servito dal ramo `ticket-detail` di `Themes/Sixteen/resources/views/pages/[container0]/[slug0]/index.blade.php` (condizione `ctype_digit($slug0)`): chiama `BuildPublicTicketDetailAction`, risponde 404 se la segnalazione non e' pubblica, aggiunge `data-dc` a `.page-content` e include `components/pages/ticket-detail.blade.php`, che include il blocco `components/blocks/ticket-detail/design-comuni.blade.php` (DCP-148). Il blocco usa `coordinate-picker-lit` in sola lettura con icona del tipo e colore dello stato (DCP-152, vedi [coordinate-picker](../coordinate-picker.md)).

Parametro base: `FIXCITY_BASE_URL` (default dev `http://127.0.0.1:8000`). Per MAUVE/PSI server-side usare **staging HTTPS** documentato in infra.

---

## Strumenti per pagina

| Pagina | Playwright | PSI | MAUVE++ | GSC |
|--------|------------|-----|---------|-----|
| Login | sì (locale) | staging | sì | no |
| Ticket detail | sì | staging | sì | no |
| Home / elenco | sì | staging | campione | no |
| Sitemap / indicizzazione | — | — | — | sì (property) |

---

## Riferimenti

- [ui-ai-tooling-on-demand.md](./ui-ai-tooling-on-demand.md)
- [visual-parity-verification-rule.md](./visual-parity-verification-rule.md)
- Hub progetto: [mcp-validation-quality-gate.md](../../../../../../docs/wiki/mcp-validation-quality-gate.md)
