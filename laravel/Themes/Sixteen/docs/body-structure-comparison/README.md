# Body Structure Comparison Results

Canonical output directory for HTML parity reports.

## How To Run

```bash
bashscripts/html/html-structure-compare.sh segnalazione-dettaglio \
  --output-dir laravel/Themes/Sixteen/docs/body-structure-comparison \
  --threshold 90
```

## Output Layout

- `<page>/report.md`
- `<page>/diff_details.json`
- `<page>/reference-body.html`
- `<page>/local-body.html`
- `<page>/reference-structure.json`
- `<page>/local-structure.json`

## Current Index

See [`INDEX.md`](./INDEX.md) for parity scores and page-level artifacts.

## Governance

- Bash scripts are reusable and project-agnostic.
- Theme-specific artifacts stay under `laravel/Themes/Sixteen/docs/...`.
- Raw HTML snapshots for manual analysis go in `laravel/Themes/Sixteen/docs/prompts/<page>/`.

- Priority rule: structural HTML parity is essential first; visual/functional parity is addressed after via Tailwind @apply + Alpine.js.
