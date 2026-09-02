---
status: done
scope: module:Xot
type: documentation
updated: 2026-09-01
qmd: "public_path public_html document root regola agente"
---

# Story: public_path → public_html (regola permanente)

## User story

Come agente o sviluppatore sul monorepo Laraxot,
voglio che la regola «`public_path` = `public_html`» sia impossibile da dimenticare,
così asset, publish, PDF e config Vite non puntano a `laravel/public` per errore.

## Acceptance criteria

- [x] Regola SSoT in `Modules/Xot/docs/wiki/rules/public-path-public-html.md`
- [x] Trigger in `docs/wiki/rules/00-TRIGGER_MAP.md`
- [x] Memoria second brain `bashscripts/ai/wiki/memories/public-path-public-html.md`
- [x] Cursor rule `.cursor/rules/public-path-public-html.mdc`
- [x] Pitfall in `AGENTS.md`
- [x] Fix bind test `CreatesApplication`: `../public_html`
- [x] Backlink temi (`Themes/Zero/docs/00-index.md`)

## Riferimenti

- [public-path-public-html.md](../wiki/rules/public-path-public-html.md)
- [document-root-public-html.md](../wiki/concepts/document-root-public-html.md)
