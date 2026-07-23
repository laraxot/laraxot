# Sixteen Theme Scripts Index

> Utility screenshot/parity scripts executed with Playwright or Puppeteer.

## Groups

| Group | Contents |
|---|---|
| Visual Parity | `batch-parity.cjs`, `check-all-pages.cjs`, `check-header-local.cjs`, `dati-deep.cjs`, `detailed-visual-compare.cjs`, `quick-style-compare.cjs`, `visual-compare-02-dati.cjs` |
| Screenshots | `take-screenshots-homepage.cjs`, `take-screenshots-phase2.cjs`, `take-screenshots-segnalazioni.cjs`, `take-screenshots-v2.cjs`, `verify-stepper.cjs` |
| Debug / Investigation | `inspect-fixcity-admin-ticket-create-map.cjs`, `map-picker-smoke.cjs` |

## Run

```bash
cd scripts
node batch-parity.cjs          # batch HTML parity for all 7 segnalazione pages
node check-all-pages.cjs       # full HTML-parity + screenshot pass
node check-header-local.cjs    # Puppeteer: header bounding-box dump
node dati-deep.cjs             # deep computed-styles for segnalazione-02-dati
node detailed-visual-compare.cjs  # multi-viewport parity for segnalazione-01-privacy
node quick-style-compare.cjs   # quick computed-style diff for segnalazione-01-privacy
node visual-compare-02-dati.cjs   # screenshots + styles for 02-dati
node take-screenshots-homepage.cjs
node take-screenshots-phase2.cjs
node take-screenshots-segnalazioni.cjs
node take-screenshots-v2.cjs
node verify-stepper.cjs

# investigative
node inspect-fixcity-admin-ticket-create-map.cjs
node map-picker-smoke.cjs
```

## Reference / Local URLs

| Target | Reference | Local |
|---|---|---|
| homepage | `itàlia…/homepage.html` | `localhost/it/tests/homepage` |
| ticket-list | `itàlia…/ticket-list.html` | `localhost/it/tests/ticket-list` |
| segnalazione-01-privacy | `itàlia…/segnalazione-01-privacy.html` | `localhost/it/tests/segnalazione-01-privacy` |
| segnalazione-02-dati | `itàlia…/segnalazione-02-dati.html` | `localhost/it/tests/segnalazione-02-dati` |
| segnalazione-03-riepilogo | `itàlia…/segnalazione-03-riepilogo.html` | `localhost/it/tests/segnalazione-03-riepilogo` |
| segnalazione-04-conferma | `itàlia…/segnalazione-04-conferma.html` | `localhost/it/tests/segnalazione-04-conferma` |
## Move log (May 16 2026)

| Script | Description |
|---|---|
| `batch-parity.cjs` | Batch CSS-parity across all 7 segnalazione pages; extracts 9 CSS props for body, h1–h2, button, card, a |
| `check-all-pages.cjs` | HTML parity check + screenshots for all 6 pages; computes font-distribution histograms |
| `check-header-local.cjs` | Puppeteer: bounding-box + computed-styles for three header wrappers |
| `dati-deep.cjs` | Deep dive for segnalazione-02-dati: full-page + section screenshots + computed-style diff |
| `detailed-visual-compare.cjs` | Multi-viewport (desktop/tablet/mobile) screenshots + computed-styles + JSON diff for segnalazione-01-privacy |
| `postcss.config.cjs` | **Kept in root** – standard PostCSS config (postcss-import, nesting, autoprefixer) |
| `quick-style-compare.cjs` | Quick font-family / color / spacing comparison for segnalazione-01-privacy |
| `take-screenshots-homepage.cjs` | Homepage screenshots (reference + local, desktop + mobile) |
| `take-screenshots-phase2.cjs` | ticket-list screenshots at 3 viewports |
| `take-screenshots-segnalazioni.cjs` | Single-page screenshot helper for ticket-list |
| `take-screenshots-v2.cjs` | Post-fix homepage viewport screenshot |
| `verify-stepper.cjs` | Stepper component screenshots at 3 viewports |
| `visual-compare-02-dati.cjs` | Full-page + computed-styles comparison for segnalazione-02-dati; saves JSON pair |

---

## Pre-existing scripts

### inspect-fixcity-admin-ticket-create-map.cjs

- **moved by**: earlier cleanup (was already in `scripts/`)
- **deps**: Playwright (`chromium`), fs
- **run**: `node scripts/inspect-fixcity-admin-ticket-create-map.cjs`
- **target**: http://127.0.0.1:8001/fixcity/admin/tickets/create?step=form.data%3A%3Adata%3A%3Awizard-step
- **env creds**: FIXCITY_ADMIN_EMAIL / FIXCITY_ADMIN_PASSWORD — auto-loaded from `laravel/.env` if missing
- **output**: JSON on stdout + screenshot `scripts/fixcity-admin-ticket-create-map.png`
- **snapshot fields**: mapContainer bbox, leaflet bbox, marker count, tile count, body/html class names, outerHTML snippet
- **fail**: when host is absent OR JS errors OR HTTP failures
- **individual doc**: `docs/scripts/inspect-fixcity-admin-ticket-create-map.md`

### map-picker-smoke.cjs

- **moved by**: earlier cleanup (was already in `scripts/`)
- **deps**: Playwright (`chromium`)
- **run**: `node scripts/map-picker-smoke.cjs`
- **target**: http://127.0.0.1:8000/it/tests/segnalazione-crea?step=form.dati-della-segnalazione%3A%3Adata%3A%3Awizard-step
- **output**: JSON summary on stdout + screenshot scripts/map-picker-smoke.png
- **checks**: JS errors, failed requests, marker visible, marker count, coordinate-changed after click
- **fail**: when coords did not change OR marker asset errors OR failed requests
- **individual doc**: `docs/scripts/map-picker-smoke.md`

---

## Moved scripts detail (May 16 2026)

| Script | Pages / Target | Key Output |
|---|---|---|
| batch-parity.cjs | 7 segnalazione pages | CSS match % table → stdout |
| check-all-pages.cjs | 7 segnalazione pages | HTML parity + font hist + screenshots |
| check-header-local.cjs | segnalazione-crea (single) | Header bbox + computed styles |
| dati-deep.cjs | segnalazione-02-dati | Section PNGs + computed-style diff |
| detailed-visual-compare.cjs | segnalazione-01-privacy | Multi-viewport PNGs + JSON diff |
| postcss.config.cjs | *(kept in theme root)* | — |
| quick-style-compare.cjs | segnalazione-01-privacy | JSON pair (ref / loc styles) |
| take-screenshots-homepage.cjs | homepage | Desktop + mobile ref+loc PNGS |
| take-screenshots-phase2.cjs | ticket-list | 3-viewport ref+loc PNGS |
| take-screenshots-segnalazioni.cjs | ticket-list | Full-page + viewport PNGS |
| take-screenshots-v2.cjs | homepage (post-fix) | Local viewport + full-page |
| verify-stepper.cjs | segnalazione-02-dati | Stepper PNGS at 3 viewports |
| visual-compare-02-dati.cjs | segnalazione-02-dati | Full-page + viewport + style-JSON pair |
