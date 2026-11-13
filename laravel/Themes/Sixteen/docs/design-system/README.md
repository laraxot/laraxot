---
title: "Sixteen DesignComuni design system"
type: design-system-index
owner: Themes/Sixteen
status: draft
updated: 2026-10-11
tags: [docs, sixteen, design-system]
qmd: "ownership and usage sixteen"
---

# Ownership and usage

This directory is the visual source of truth for the Sixteen theme. It belongs
inside the theme because tokens, component states, typography, spacing and page
patterns are implementation decisions of Sixteen, not global FixCity domain
rules and not reusable infrastructure for other themes.

`MASTER.md` contains the shared visual contract. Page-specific exceptions belong
in `pages/` and must link back to the relevant BMAD story and accessibility
evidence. The planning and traceability artifacts live in the theme's `docs/bmad`,
`docs/investigation`, `docs/stories`, `docs/decision-log` and `docs/wiki` areas.

The repository-level `bmad-output/front-office-design-comuni` remains the
cross-cutting planning workspace; it must reference this file instead of
duplicating the theme's design source of truth.
