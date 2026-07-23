---
title: "Dependencies (Module UI)"
type: concept
tags: [dependencies]
created: 2026-07-14
updated: 2026-07-14
qmd: "dependencies dependencies (module ui)"
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

# Dependencies (Module UI)

Canonical dependency map:

- [../../../../docs/dependencies.md](../../../../docs/dependencies.md)

Module/theme specific notes:

- UI è un modulo infrastrutturale: **non deve mai dipendere da moduli dominio** (GEO, Ptv, Sigma, ecc.)
- I moduli dominio possono dipendere da UI, mai il contrario.
- Questa regola previene dipendenze circolari e mantiene UI agnostica rispetto al dominio applicativo.
- Violazione nota e corretta: `InteractiveMap.php` (rimosso, apparteneva a GEO).

Installed packages index:

- [../../../../docs/packages/index.md](../../../../docs/packages/index.md)
