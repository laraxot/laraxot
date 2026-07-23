# Segnalazione Pages Parity - Index

**Project:** Design Comuni Italia - Segnalazione Workflow
**Status:** ✅ Core Work Complete - All pages return HTTP 200
**Last Updated:** 2026-04-07

---

## Pages Covered

| # | Page | Reference | Local Blade | Status |
|---|------|-----------|-------------|--------|
| 1 | segnalazione-dettaglio | `segnalazione-dettaglio.hbs` | `segnalazione-dettaglio.blade.php` | ✅ 90% |
| 2 | segnalazione-01-privacy | `segnalazione-01-privacy.hbs` | `flow.ticket/01-privacy.blade.php` | ✅ 90% |
| 3 | segnalazione-02-dati | `segnalazione-02-dati.hbs` | `flow.ticket/02-dati.blade.php` | ✅ 85% |
| 4 | segnalazione-03-riepilogo | `segnalazione-03-riepilogo.hbs` | `flow.ticket/03-riepilogo.blade.php` | ✅ 85% |
| 5 | segnalazione-04-conferma | `segnalazione-04-conferma.hbs` | `flow.ticket/04-conferma.blade.php` | ✅ 90% |
| 6 | segnalazione-area-personale | `segnalazione-area-personale.hbs` | `flow/area-personale/dashboard.blade.php` | ⚠️ 50% |
| 7 | ticket-list | `ticket-list.hbs` | `segnalazioni/layout.blade.php` | ✅ 85% |

---

## Documentation

| Document | Description |
|----------|-------------|
| [HTML_STRUCTURE_ANALYSIS.md](./HTML_STRUCTURE_ANALYSIS.md) | Detailed HTML structure comparison |
| [CSS_JS_PARITY_COMPLETE.md](./CSS_JS_PARITY_COMPLETE.md) | CSS/JS parity work summary |
| [STATUS_REPORT_2026_04_07.md](./STATUS_REPORT_2026_04_07.md) | Current session status |
| [reference-html/](./reference-html/) | Reference HBS templates |
| [screenshots/](./screenshots/) | Screenshot captures |

## Translation Files

| Language | File | Strings |
|----------|------|---------|
| Italian | `lang/it/segnalazione.php` | 60+ |
| English | `lang/en/segnalazione.php` | 60+ |

---

## Blade Templates

| Template | Path |
|----------|------|
| segnalazione-dettaglio | `resources/views/components/blocks/tests/ticket-detail.blade.php` |
| 01-privacy | `resources/views/components/blocks/flow.ticket/01-privacy.blade.php` |
| 02-dati | `resources/views/components/blocks/flow.ticket/02-dati.blade.php` |
| 03-riepilogo | `resources/views/components/blocks/flow.ticket/03-riepilogo.blade.php` |
| 04-conferma | `resources/views/components/blocks/flow.ticket/04-conferma.blade.php` |
| ticket-list | `resources/views/components/blocks/segnalazioni/layout.blade.php` |

---

## CSS

- **Main CSS:** `resources/css/style-apply.css` (400+ new Design Comuni classes)
- **Bootstrap Mapping:** `resources/css/tailwind-bootstrap-mapping.css`
- **Build:** 1,005 KB → 116 KB gzip

---

## Cross-References

- → [../00-index.md](../00-index.md) - Theme docs index
- → [../css-js-parity-2026-04-04.md](../css-js-parity-2026-04-04.md) - Previous CSS/JS parity
- → [../../../../../docs/MODULE_DOCS_INDEX.md](../../../../../docs/MODULE_DOCS_INDEX.md) - Master project index
- → [../../../../../bashscripts/docs/README.md](../../../../../bashscripts/docs/README.md) - Scripts index

---

*Index updated: 2026-04-07*
