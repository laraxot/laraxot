---
title: AI Agent Prompts Junction
type: concept
created: 2026-07-13
updated: 2026-07-13
tags: [ai, prompts, agents, github, junction]
---

# AI Agent Prompts Junction

`bashscripts/ai/.agents/prompts` is the canonical prompt directory.

`.github/prompts` must be a symbolic link to:

```text
../bashscripts/ai/.agents/prompts
```

## Why

The prompt content belongs to the shared agent harness, not to GitHub. GitHub needs the path for compatibility, but keeping real files in both places creates drift between Copilot, Codex, Cursor, Claude, Gemini and the other local agent adapters.

## Rule

- Move real prompt directories into `bashscripts/ai/.agents/prompts`.
- Keep `.github/prompts` as a symlink only.
- Ignore the symlink from `.github/.gitignore` with `prompts`.
- Do not create a second prompt archive under `.github`.
