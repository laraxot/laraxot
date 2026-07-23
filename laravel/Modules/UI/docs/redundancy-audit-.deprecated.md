---
title: "UI redundancy audit 2026-05-21"
type: audit
module: UI
tags: [redundancy, components, config, design-system]
created: 2026-05-21
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

# UI redundancy audit 2026-05-21

High-risk findings:
- Config files exist in both `Config/` and `config/`, including `config.php`, `laravel-localization.php`, and `laravellocalization.php`.
- Many UI components are byte-identical with `Modules/User`, including `button`, `modal`, `input`, `checkbox`, `badge`, `link`, `nav-link`, `text-link`, `placeholder`, and marketing/layout components.
- Block stats are duplicated under both `archived/` and `superseded/`.
- Docs have case-only duplicates such as `architecture-1.md` and `architecture.md`, plus duplicate index variants.

Risk:
- UI should be the shared owner. Duplicating primitives in User makes fixes diverge.
- `Config/` vs `config/` is a case-only package portability problem.
- `archived`/`superseded` component folders are runtime-visible if Blade discovery includes them.

Suggested cleanup order:
1. Confirm service providers load lowercase `config/`, then remove uppercase mirrors in a separate issue.
2. Make `Modules/UI` the canonical owner for shared primitives; replace User copies with references or wrappers.
3. Move non-runtime old block variants out of runtime component discovery paths.
