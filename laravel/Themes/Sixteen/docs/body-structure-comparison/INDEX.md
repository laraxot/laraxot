# Body Structure Comparison

Canonical output area for HTML body structure comparisons between Design Comuni reference pages and Sixteen theme implementations.

## Pages Compared

### segnalazione-area-personale

**Parity score (struttura `compare-html.sh`)**: **48.0%** (2026-04-20) — obiettivo progetto ≥90% non ancora raggiunto; vedi [`./segnalazione-area-personale/report.md`](./segnalazione-area-personale/report.md).

### segnalazione-01-privacy

**Parity Score: 93%** ✅ PASS (405/431 reference elements matched)

Artifacts in [`./segnalazione-01-privacy/`](./segnalazione-01-privacy/):
- [`report.md`](./segnalazione-01-privacy/report.md) — Detailed comparison report
- [`reference.html`](./segnalazione-01-privacy/reference.html) — Reference HTML (cleaned)
- [`local.html`](./segnalazione-01-privacy/local.html) — Local HTML (cleaned)
- Full output: [`../../prompts/segnalazione-01-privacy/body-structure-comparison/`](../../prompts/segnalazione-01-privacy/body-structure-comparison/)

### ticket-list

**Parity Score: 77.8%** (603/775 elements identical)

Artifacts in [`./ticket-list/`](./ticket-list/):
- [`report.md`](./ticket-list/report.md) — Detailed report with identical, missing, different, and extra elements
- [`diff_details.json`](./ticket-list/diff_details.json) — Machine-readable parity summary
- [`reference-body.html`](./ticket-list/reference-body.html) — Reference body (no `<script>`/`<style>`)
- [`local-body.html`](./ticket-list/local-body.html) — Local body (no `<script>`/`<style>`)
- [`reference-structure.json`](./ticket-list/reference-structure.json) — Parsed reference tree
- [`local-structure.json`](./ticket-list/local-structure.json) — Parsed local tree

### segnalazione-dettaglio

**Parity Score: 45.5%** (366/804 elements identical)

Artifacts in [`./segnalazione-dettaglio/`](./segnalazione-dettaglio/):
- [`report.md`](./segnalazione-dettaglio/report.md) — Detailed report with identical, missing, different, and extra elements
- [`diff_details.json`](./segnalazione-dettaglio/diff_details.json) — Machine-readable parity summary
- [`reference-body.html`](./segnalazione-dettaglio/reference-body.html) — Reference body (no `<script>`/`<style>`)
- [`local-body.html`](./segnalazione-dettaglio/local-body.html) — Local body (no `<script>`/`<style>`)
- [`reference-structure.json`](./segnalazione-dettaglio/reference-structure.json) — Parsed reference tree
- [`local-structure.json`](./segnalazione-dettaglio/local-structure.json) — Parsed local tree

### segnalazione-02-dati

**Parity Score: TBD%** (pending comparison)

Artifacts in [`./segnalazione-02-dati/`](./segnalazione-02-dati/) *(to be generated)*:
- Reference HTML: [`../../prompts/segnalazione-02-dati/reference_segnalazione-02-dati.html`](../../prompts/segnalazione-02-dati/reference_segnalazione-02-dati.html)
- Analysis: [`../../prompts/segnalazione-02-dati/html-parity-analysis.md`](../../prompts/segnalazione-02-dati/html-parity-analysis.md)

Run: `bashscripts/html/html-structure-compare.sh segnalazione-02-dati`

## Tooling

Agnostic tooling lives in `bashscripts`:
- [`bashscripts/html/html-structure-compare.sh`](../../../bashscripts/html/html-structure-compare.sh) — Bash wrapper
- [`bashscripts/html/compare-html-body.py`](../../../bashscripts/html/compare-html-body.py) — Python engine
- [`bashscripts/docs/html/README.md`](../../../bashscripts/docs/html/README.md) — Tool documentation

## Project Bridge

- **Root bridge**: [`docs/html-structure-comparison.md`](../../../docs/html-structure-comparison.md)
- **Theme docs index**: [`../00-index.md`](../00-index.md)

## Governance

- `bashscripts` stays reusable and project-agnostic.
- Theme-specific outputs stay under `laravel/Themes/Sixteen/docs/...`.
- Source HTML snapshots go to [`../prompts/<page>/`](../prompts/).
- The root bridge note for this repo lives in [`docs/html-structure-comparison.md`](../../../docs/html-structure-comparison.md).
