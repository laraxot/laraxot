---
title: "Design Spec – Shared AI‑Agent Documentation Layout (LLM‑Wiki)"
type: concept
tags: [2026, llm, wiki, layout]
created: 2026-07-14
updated: 2026-07-14
qmd: "2026-04-15-llm-wiki-layout-design.deprecated design spec – shared ai‑agent documentation layout (llm‑wiki)"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./fixcity-hero-marketing-1.md"
  - "./fixcity-hero-marketing.md"
  - "./laraxot-hero-marketing-1.md"
  - "./laraxot-hero-marketing.md"
  - "./llm-wiki-layout-design.md"
  - "./segnalazione-wizard-design.md"
---

# Design Spec – Shared AI‑Agent Documentation Layout (LLM‑Wiki)

**Date:** 2026‑04‑15

## Goal
Create a single source of truth for the large AI‑agent markdown files (`gemini.md`, `claude.md`, `qwen.md`) and ensure every module and theme in the repository has a `docs/wiki/` (and accompanying `llm‑wiki` assets) while avoiding duplicated content.

## Architecture Overview
```
.agents/
└─ docs/
   └─ ai-agents/
      ├─ shared/          # ONE set of SOLID markdown pieces
      │   ├─ 01-overview.md
      │   ├─ 02-setup.md
      │   ├─ 03-usage.md
      │   └─ 04-reference.md
      ├─ gemini/          # tiny stub linking to shared files
      │   └─ README.md
      ├─ claude/          # tiny stub linking to shared files
      │   └─ README.md
      └─ qwen/            # tiny stub linking to shared files
          └─ README.md
```

*All three AI‑agent docs will point to the same SOLID files, eliminating duplication.*

## File Details
### Shared SOLID files (`./shared/`)
- **01‑overview.md** – High‑level description of the AI‑agent system, terminology, and purpose.
- **02‑setup.md** – Installation, configuration, and environment‑variable requirements.
- **03‑usage.md** – Command‑line examples, API surface, and integration points.
- **04‑reference.md** – Exhaustive reference table, supported models, and versioning.

Each file starts with a short abstract (max 2 sentences) followed by the main content.

### Stub README files (`gemini/`, `claude/`, `qwen/`)
```markdown
# <AGENT> AI Agent Documentation

> This documentation is shared across all AI‑agent docs. The files below are linked to the single source in `../shared/`.

- [01 Overview](../shared/01-overview.md)
- [02 Setup](../shared/02-setup.md)
- [03 Usage](../shared/03-usage.md)
- [04 Reference](../shared/04-reference.md)
```
Replace `<AGENT>` with *Gemini*, *Claude*, or *Qwen* respectively.

## LLM‑Wiki Reminder Update
Add the following bullet to the root `claude.md` (project‑wide guidance) so future contributors remember the requirement:
```markdown
- **LLM‑Wiki rule** – Every module and theme must contain a `docs/wiki/` directory (and the corresponding `llm‑wiki` scaffolding). This directory holds the compiled wiki pages; the raw markdown lives under `docs/`.
```
The bullet should be placed under the **“leggi prima”** section or a dedicated “Documentation conventions” heading.

## Migration Steps
1. Create `./.agents/docs/ai-agents/shared/` and add the four SOLID markdown files.
2. Add the three stub `README.md` files under `gemini/`, `claude/`, `qwen/`.
3. Remove the now‑redundant large `gemini.md`, `claude.md`, `qwen.md` files (or replace them with a one‑line redirect to the stub README).
4. Update `claude.md` with the LLM‑Wiki reminder bullet.
5. Run `qmd embed` to index the new markdown files for the local search engine.

## Success Criteria
- No duplicated content across the three AI‑agent docs.
- All three agents render identical documentation when their stub README is opened.
- The LLM‑Wiki reminder appears in `claude.md` and is visible in the project‑wide documentation index.
- `qmd embed` runs without errors and the new docs are searchable.

---
*Spec written according to the Superpowers brainstorming workflow. Please review and approve before proceeding to implementation.*