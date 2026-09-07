---
name: llm-wiki rule
description: Ensure every module and theme includes a `docs/wiki/` directory and related llm‑wiki scaffolding.
---

# LLM‑Wiki Rule

<<<<<<< HEAD
<<<<<<< HEAD
**Rule:** All modules and themes must contain a `docs/wiki/` directory (with compiled wiki pages) and the corresponding `llm‑wiki` scaffolding (templates, AGENTS.md, etc.).
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
**Rule:** All modules and themes must contain a `docs/wiki/` directory (with compiled wiki pages) and the corresponding `llm‑wiki` scaffolding (templates, AGENTS.md, etc.).
>>>>>>> a988596b (first)
**Rule:** All modules and themes must contain a `docs/wiki/` directory (with compiled wiki pages) and the corresponding `llm‑wiki` scaffolding (templates, agents.md, etc.).

**Why:** The LLM‑wiki is the unified documentation source; missing folders break the wiki build and QMD indexing.

**How to apply:** When creating a new module or theme, add the `docs/wiki/` folder (including `index.md`, `log.md`, and any overview files) and the `docs/llm-wiki/` support files before committing.
