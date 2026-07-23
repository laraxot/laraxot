# Merge Conflict Marker Cleanup

> Updated: 2026-04-21

## Rule

Theme conflict cleanup must be coordinated with other agents by taking sparse batches and re-reading each file before editing.

For Sixteen, prioritize files that affect Vite/build/runtime rendering before docs-only historical reports:

- `package.json`
- `vite.config.js`
- `resources/js/app.js`
- `resources/css/app.css`
- Blade components under `resources/views/`

Docs under `docs/` can be cleaned after runtime files, but must not be ignored permanently.

