# Validazione MCP — pagine FO Sixteen

**Boundary:** il tema Sixteen **non** implementa motori MAUVE/PSI/GSC; espone le URL FO che entrano nel gate agente ([mcp-validation-quality-gate.md](../../../../../docs/wiki/mcp-validation-quality-gate.md)).

**Story:** [STORY-137](../../../../../docs/stories/STORY-137-mcp-validation-mauve-pagespeed-gsc.md)

---

## URL smoke (ordine consigliato)

| Priorità | Path | Perché |
|----------|------|--------|
| 1 | `/it/auth/login` | Form Filament, label, errori, contrasto ([auth-login-ux-fixes](./auth-login-ux-fixes.md)) |
| 2 | `/it/tickets/{id}` | Widget `Ticket\ViewWidget` + Infolist, mappa, media |
| 3 | `/it` | Homepage, map-lit, hero |
| 4 | Elenco segnalazioni | Tabs Filament 5.x, filtri |

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
- Hub progetto: [mcp-validation-quality-gate.md](../../../../../docs/wiki/mcp-validation-quality-gate.md)
