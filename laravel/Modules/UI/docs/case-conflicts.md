---
title: "Case-Insensitive File Conflicts"
type: concept
tags: [case, conflicts]
created: 2026-07-14
updated: 2026-07-14
qmd: "case-conflicts case-insensitive file conflicts"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./04-datas.md"
  - "./advanced-form-components-1.md"
  - "./advanced-form-components.md"
  - "./agent-confidence-discipline.md"
  - "./agent-confidence-protocol.md"
  - "./agent-edit-discipline.md"
---

# Case-Insensitive File Conflicts

File duplicati rilevati nel modulo `UI`:

- `Modules/UI/.github`: `CONTRIBUTING.md`, `contributing.md`
- `Modules/UI/.github`: `SECURITY.md`, `security.md`
- `Modules/UI/docs`: `README.md`, `readme.md`
- `Modules/UI/docs/filament`: `listrecords-1.md`, `listrecords.md`

Uniformare ciascuna coppia scegliendo un'unica versione (in genere `README.md`, `CONTRIBUTING.md`, ecc.) e rimuovere i duplicati.
